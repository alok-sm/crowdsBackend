<?php
use Carbon\Carbon;
use App\TaskBuffer;
use App\Client;
use App\Task;
use App\Domain;
use App\Answer;

function submission($task_id, $user_status){
	$response= DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->where('answers.task_id',$task_id)->whereNotIn('answers.data', ['null', 'timeout'])->limit(5)->lists('data');
	$answer = DB::table('tasks')->where('id',$task_id)->select('answer_type')->first();
	$answer_type = $answer->answer_type;
	$total = sizeof($response);

	if(sizeof($response) < 5)
		$response="Not enough data";
		
	else if($answer_type == "select"){
		$response = DB::table('answers')->select('answers.data as data',DB::raw("count('answers.data') as total"))->join('users', 'users.id', '=', 'answers.user_id')->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->groupBy('data')->get();
		$hist = [];
		foreach( $response as $item )
			$hist[$item->data] = $item->total;
		$response = $hist;
	}
	
	else if($answer_type == "int"){
		if ($total % 2 != 0)
			$total += 1;
		$median = (int)(ceil($total / 2));
		$upper_index = (int)(ceil($total / 4) - 1);
		$lower_index = (int)(ceil($total * 3 / 4) - 1);

		$median = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->whereNotIn('answers.data', ['null', 'timeout'])->where('answers.task_id', $task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"), 'asc')->skip($median)->limit(1)->first();
		$median_up = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->whereNotIn('answers.data', ['null', 'timeout'])->where('answers.task_id', $task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"), 'asc')->skip($upper_index)->limit(1)->first();
		$median_down = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->whereNotIn('answers.data', ['null', 'timeout'])->where('answers.task_id', $task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"), 'asc')->skip($lower_index)->limit(1)->first();

		$iqr = ($median_down->data - $median_up->data);
		$median = $median->data;

		$response = array('count'=>$total, 'median'=>$median, 'interquartile_range'=>$iqr);
	}
	return $response;
}

function first_submission($task_id, $user_status){
	$response= DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->limit(5)->lists('data');
	if(sizeof($response)<5)
		$response="Not enough data";
	return $response;
}

function recent_submission($task_id, $user_status){
	$response = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->orderBy('answers.id','desc')->limit(5)->lists('data');
	if(sizeof($response)<5)
		$response="Not enough data";
	return $response;
}	

function confident_submission($task_id, $user_status){
	$response = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->orderBy('answers.confidence', 'desc')->limit(5)->lists('data');
	if(sizeof($response)<5)
		$response="Not enough data";
	return $response;
}

function statistics($user_id, $task_id){
	$user = Client::find($user_id);
	$status = $user->status;

	if ($status == 0)
		return array();
	else if($status == 1)
		$stats = submission($task_id, $status);
	else if($status == 2)
		$stats = recent_submission($task_id, $status);
	else if($status == 3)
		$stats = first_submission($task_id, $status);
	else if($status == 4)
		$stats = confident_submission($task_id, $status);

	$response_array = array("stats" => $stats);
	return $response_array;
}



function task_detail($task_id){
	$task = Task::find($task_id);
	$task_json = array("id" => $task_id, "title" => $task->title, "type" => $task->type, "data" => $task->data, "answer_type" => $task->answer_type, "answer_data" => $task->answer_data, "units" => $task->units);
	return $task_json;
}



function task_timing($user_id)
{
	$answer = Answer::where('user_id', $user_id)->where('data', 'null')->orderBy('id', 'desc')->first();
	if (isset($answer))
	{
		$time_diff = $answer->created_at->diffInSeconds();
		if ($time_diff < $answer->task->domain->time_limit){
			$task_json = task_detail($answer->task_id);
			return array("task" => $task_json, "timeout" => ($answer->task->domain->time_limit - $time_diff));
		}
		else{
			$answer->data = 'timeout';
			$answer->ignore_save_condition = true;
			$answer->save();
		}
	}
	return false;
}

function create_new_answer($task_id, $user_id)
{
	$answer = new Answer;
	$answer->task_id = $task_id;
	$answer->user_id = $user_id;
	$answer->data = 'null';
	$answer->confidence = 0;
	$answer->submitted_at = Carbon::now();
	return $answer->save();
}


function task_status($user_id)
{
	$task_time = task_timing($user_id);
	if($task_time != false)
	{
		$task_json = $task_time["task"];
		$timeout = $task_time["timeout"];

		$user = Client::find($user_id);
		$result = TaskBuffer::where('user_id', $user_id)->orderBy('id','desc')->first();
		$num_task = count($result->task_id_list);

		$response_array = statistics($user_id, $task_json["id"]);
		$response_array += array("status"=>"success", "task"=>$task_json, "remaining"=>$num_task, "timeout" => $timeout, "experimental_condition" => $user->status);
		return $response_array;
	}
	return false;
}

function domain_json($domain_id)
{
	$domain = Domain::find($domain_id);
	$domain_json = array("id" => $domain_id, "name" => $domain->name, "description" => $domain->description);
	return $domain_json;
}

function create_task_buffer($domain_id, $user_id)
{
	$tasks = Task::where('domain_id', $domain_id)->lists('id');
	$tb = new TaskBuffer;
	$tb->domain_id = $domain_id;
	$tb->user_id = $user_id;
	$tb->task_id_list = $tasks;
	$tb->points = 0;
	return $tb->save();
}

// Select a particular domain from the list of domains
function select_domain($domain_id_list)
{
	$size = sizeof($domain_id_list);
	$index = rand(0, $size-1);
	$domain_id = $domain_id_list[$index];
	return $domain_id;
}

// Assigns domain to a user
function assign_random_domain($user_id)
{
	$user_domains = TaskBuffer::where('user_id', $user_id)->lists('domain_id');
	$all_domains = Domain::lists('id');
	$diff = array_diff($all_domains, $user_domains);
	$diff = array_values($diff);

	if (sizeof($diff) > 0)
	{
		$domain_id = select_domain($diff);
		if (create_task_buffer($domain_id, $user_id))
			$response_array = array("status" => "success");
		else
			$response_array = array("status" => "fail");
	}
	else
		$response_array = array("status" => "done");
	return $response_array;
}


// Select's a random task
function task_select($domain_id, $user_id, $task)
{
	$ans = Answer::where('user_id', $user_id)->join('tasks', 'answers.task_id', '=', 'tasks.id')->where('tasks.domain_id', $domain_id)->select('answers.task_id')->count();
	if ($ans < 10)
	{
		$first_ten = [];
		for($i=0; $i<10-$ans; $i++)
			$first_ten[$i] = $task[$i];
		$task = $first_ten;
	}
	$num = count($task);
	$index = rand(0, $num-1);
	$task_id = $task[$index];
	$task_json = task_detail($task_id);

	if (create_new_answer($task_id, $user_id)){

		$user = Client::find($user_id);
		$result = TaskBuffer::where('user_id', $user_id)->orderBy('id','desc')->first();
		$num_task = count($result->task_id_list);

		$response_array = statistics($user_id, $task_id);
		$response_array += array("status"=>"success", "task"=> $task_json, "remaining"=>$num_task, "timeout" => $result->domain->time_limit, "experimental_condition" => $user->status);
		$response_array += array("task"=>$task_json);
		return $response_array;
	}
	else{
		echo "Failed selecting task";
		return array("status" => "fail");
	}
}

function total_domains($user_id)
{
	return Domain::all()->count();
}

function remaining_domains($user_id)
{
	$domain_done = Client::find($user_id)->task_buffers()->count();
	$domain_total = total_domains($user_id);
	$num_domains = $domain_total-$domain_done+1;
	return $num_domains;
}

function new_domain($user_id)
{
	$response = assign_random_domain($user_id);
	if ($response["status"] == "success")
		return helper($user_id);
	else
		return $response;
}

function find_rank($user_id)
{
	$task_buffer = TaskBuffer::where('user_id', $user_id)->where('task_id_list', '[]')->orderBy('id','desc')->first();
	if (isset($task_buffer))
	{
		// Compare answers with tasks answers; 
		return TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('points', '<', $task_buffer->points)->count();
	}
	else
		return null;
}

function users($user_id)
{
	$task_buffer = TaskBuffer::where('user_id', $user_id)->where('task_id_list', '[]')->orderBy('id','desc')->first();
	return (TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->count());
}

function helper($userId)
{
	// Check if there is a task buffer
	$task_buffer = TaskBuffer::where('user_id', $userId)->orderBy('id','desc')->first();

	if (isset($task_buffer))
	{
		// Case 1: When the pre-confidence value is 0
		if ($task_buffer->pre_confidence_value == 0)
			$response_array = array('status' => 'success', 'type' => 0, "domain" => domain_json($task_buffer->domain_id), "remaining" => count($task_buffer->task_id_list), "remaining_domains" => remaining_domains($userId), "total_domains" => total_domains($userId), "rank" => find_rank($userId), "total_users" => users($userId));
			// $response_array = array('status' => 'success', 'type' => 0, "domain" => domain_json($task_buffer->domain_id), "remaining" => count($task_buffer->task_id_list), "remaining_domains" => remaining_domains($userId), "total_domains" => total_domains($userId));
		else if (count($task_buffer->task_id_list) != 0){
			// Case 2: When there are pending tasks
			$task = task_status($userId);
			if ($task == false)
			{
				// Case 2a: There is no pending task with respect to timer, hence assign new task
				$response_array = task_select($task_buffer->domain_id, $task_buffer->user_id, $task_buffer->task_id_list);
			}
			else
			{
				// Case 2b: There is a pending task with respect to time. hence return it.
				$response_array = $task;	
			}
		}
		else
		{
			// Case 3: When there is no task left
			$response_array = new_domain($userId);
		}
	}
	else
	{
		// Case 4: The new user is just created
		$response_array = new_domain($userId);
	}

	return $response_array;
}

?>