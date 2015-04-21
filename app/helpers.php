<?php

use App\TaskBuffer;
use App\Client;
use App\Task;
use App\Domain;
use App\Answer;


function submission($task_id, $user_status){
	$response= DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->where('answers.task_id',$task_id)->whereNotIn('answers.data', ['no_answer', 'timeout'])->limit(5)->lists('data');
	$answer = DB::table('tasks')->where('id',$task_id)->select('answer_type')->first();
	$answer_type = $answer->answer_type;

	if(sizeof($response)==0)
		$response="Not enough data";
		
	else if($answer_type == "select"){
		$response = DB::table('answers')->select('answers.data as data',DB::raw("count('answers.data') as total"))->join('users', 'users.id', '=', 'answers.user_id')->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->groupBy('data')->get();
		$hist = [];
		foreach( $response as $item )
			$hist[$item->data] = $item->total;
		$response = $hist;
	}
	
	else if( $answer_type == "int"){
		$total = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('answers.task_id',$task_id)->count();
		$x = ($total/2+1);
		$median_r = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('answers.task_id',$task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"),'asc')->skip($x-1)->limit(1)->first();
		$median = $median_r->data;
		$iqr_l_total = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('answers.task_id',$task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"),'asc')->where('answers.data','<',$median)->count();
		$x = ($iqr_l_total/2+1);
		$iqr_l = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('answers.task_id',$task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"),'asc')->where('answers.data','<',$median)->skip($x-1)->limit(1)->first();
		$iqr_l_median = $iqr_l->data;
		$iqr_h_total = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('answers.task_id',$task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"),'asc')->where('answers.data','>',$median)->count();
		$x = ($iqr_h_total/2+1);
		$iqr_h = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.status', $user_status)->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('answers.task_id',$task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"),'asc')->where('answers.data','>',$median)->skip($x-1)->limit(1)->first();
		$iqr_h_median = $iqr_h->data;
		$iqr = $iqr_h_median - $iqr_l_median;
		$response = array('count'=>$total, 'median'=>$median, 'interquartile_range'=>$iqr);
	}
	return $response;
}


	
function first_submission($task_id, $user_status){
	$response= DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->limit(5)->lists('data');
	if(sizeof($response)<5)
		$response="Not enough data";
	return $response;
}



function recent_submission($task_id, $user_status){
	$response = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->orderBy('answers.id','desc')->limit(5)->lists('data');
	if(sizeof($response)<5)
		$response="Not enough data";
	return $response;
}	



function confident_submission($task_id, $user_status){
	$response = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->whereNotIn('answers.data', ['no_answer', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->orderBy('answers.confidence', 'desc')->limit(5)->lists('data');
	if(sizeof($response)<5)
		$response="Not enough data";
	return $response;
}



function status_check($task_json, $num_task, $user, $task_id, $timeout){
	$status = $user->status;
	if($status==1){
		$stats=submission($task_id, $status);
	}
	else if($status==2){
		$stats=recent_submission($task_id, $status);
	}
	else if($status==3){
		$stats=first_submission($task_id, $status);
	}
	else if($status==4){
		$stats=confident_submission($task_id, $status);
	}
	if($status==0)
		$response_array = array("status"=>"success", "task"=>$task_json, "remaining"=>$num_task, "timeout" => $timeout);
	else
		$response_array = array("status"=>"success", "task"=>$task_json, "remaining"=>$num_task, "timeout" => $timeout, "experimental_condition"=>$status, "stats"=>$stats);
	return $response_array;
}



function get_task_detail($task_id){
	$task_desc = Task::find($task_id);
	$taskTitle = $task_desc->title;
	$taskType = $task_desc->type;
	$taskData = $task_desc->data;
	$answerType = $task_desc->answer_type;
	$answerData = $task_desc->answer_data;
	$task_json=array("id"=>$task_id, "title"=>$taskTitle, "type"=>$taskType, "data"=>$taskData, "answer_type"=>$answerType, "answer_data"=>$answerData);
	return $task_json;
}



function task_timing($user_id)
{
	$answer = Answer::where('user_id', $user_id)->where('data', 'no_answer')->orderBy('id', 'desc')->first();
	if (isset($answer))
	{
		// $answer_id = $answer->id;
		$time_diff = $answer->created_at->diffInSeconds();
		if ($time_diff < 45){
			$task_id = $answer->task_id;
			$task_json = get_task_detail($task_id);
			$send = array($task_json, $task_id, 45 - $time_diff);
			return $send;	
		}
		else{
			// $answer = Answer::find($answer_id);
			$answer->data = 'timeout';
			$answer->ignore_save_condition = true;
			$answer->save();
		}
	}
	return false;	
}



function create_new_answer($task_id, $user_id){
	$answer = new Answer;
	$answer->task_id = $task_id;
	$answer->user_id = $user_id;
	$answer->data = 'no_answer';
	$answer->confidence = 0;
	$answer->time_taken = 0;
	return $answer->save();
}


function task_status($user_id)
{
	$task_time = task_timing($user_id);
	if($task_time!=false)
	{
		$task_json = $task_time[0];
		$task_id = $task_time[1];
		$timeout = $task_time[2];

		$user = Client::find($user_id);
		$result = TaskBuffer::where('user_id', $user_id)->orderBy('id','desc')->first();
		$task=$result->task_id_list;
		$num_task=count($task);

		$response_array = status_check($task_json, $num_task, $user, $task_id, $timeout);
		return $response_array;
	}
	return false;
}



function domain_details($domain_id)
{
	$domain_desc = Domain::where('id', $domain_id)->select('description', 'name')->first();
	$desc=$domain_desc->description;
	$name=$domain_desc->name;
	$domain_json=(array("id"=>$domain_id,"name"=>$name,"description"=>$desc));
	$response_array=array("status"=>"success","domain"=>$domain_json);
	return $response_array;
}



function create_task_buffer($domain_id, $user_id)
{
	$tasks=Task::where('domain_id',$domain_id)->lists('id');
	$tb=new TaskBuffer;
	$tb->domain_id=$domain_id;
	$tb->user_id=$user_id;
	$tb->task_id_list=$tasks;
	$tb->save();
}



function select_domain($domain_id_list)
{
	$size=sizeof($domain_id_list);
	$index=rand(0,$size-1);
	$domain_id=$domain_id_list[$index];
	return $domain_id;
}


function domain_assign_new($user_id)
{
	$domains_all = Domain::lists('id');
	$domain_id = select_domain($domains_all);
	$response_array = domain_details($domain_id);
	create_task_buffer($domain_id, $user_id);
	return $response_array;
}



function domain_assign_current($user_id)
{
	$domains = TaskBuffer::where('user_id', $user_id)->lists('domain_id');
	$domains_all = Domain::lists('id');
	$diff = array_diff($domains_all, $domains);
	$diff = array_values($diff);
	if(sizeof($diff)>0)
	{				
		$domain_id = select_domain($diff);
		$response_array = domain_details($domain_id);
		create_task_buffer($domain_id, $user_id);				
	}
	else
	{
		$response_array = array("status"=>"done");
	}
	return $response_array;
}


// Select's a random task
function task_select($domain_id, $user_id, $task, $num_task)
{
	$ans = Answer::where('user_id', $user_id)->join('tasks', 'answers.task_id', '=', 'tasks.id')->where('tasks.domain_id', $domain_id)->select('answers.task_id')->count();
	if ($ans<10)
	{
		$first_ten = [];
		for($i=0; $i<10-$ans; $i++)
			$first_ten[$i] = $task[$i];
		$task = $first_ten;
	}
	$num = count($task);
	$index = rand(0,$num-1);
	$task_id = $task[$index];
	$task_json = get_task_detail($task_id);
	if (create_new_answer($task_id, $user_id)){
		$user = Client::find($user_id);
		$timeout = 45;
		$response_array = status_check($task_json, $num_task, $user, $task_id, $timeout);
		return $response_array;
	}
	else{
		return array("status" => "fail");
	}
}



function domain_helper($userId)
{
	$result = TaskBuffer::where('user_id', $userId)->orderBy('id','desc')->first();
	if(isset($result))
	{
		$task_buffer_id=$result->id;
		$domain_id=$result->domain_id;
		$task=$result->task_id_list;
		$num_task=sizeof($task);
		
		if($num_task>0){
			$response_array = domain_details($domain_id);
		}
		else
		{
			if($result->post_confidence_value==0 and $num_task==0)
			{
				$response_array = domain_details($domain_id);
			}
			else
			{
				$response_array = domain_assign_current($userId);
			}
		}	
	}
	else if(!isset($result))
	{
		$response_array = domain_assign_new($userId);
	}
	else
	{
		$response_array = array('status' => 'fail');
	}
	return $response_array;
}



function helper($userId)
{
	// This is for assigning random domain to user
	$response = domain_helper($userId);

	if($response['status']=='done'){
		$response_array = array('status' => 'done');
	}
	else if($response['status']=='fail'){
		$response_array = array('status' => 'fail');
	}
	else if($response['status']=='success')
	{
		$response_array = task_status($userId);
		if($response_array != false)
		{
			return $response_array;
		}

		// Timeout or the Task is answered
		$result = TaskBuffer::where('user_id', $userId)->orderBy('id','desc')->first();
		
		if(isset($result))
		{
			$task = $result->task_id_list;
			$task_buffer_id = $result->id;
			$num_task = count($task);

			// All domain details
			$domain = $response['domain'];
			$domain_id = $domain['id'];
			$domain_desc = $domain['description'];
			$domain_done = Client::find($userId)->task_buffers()->count();
			$domain_total = Domain::all()->count();
			$num_domain = $domain_total-$domain_done+1;
			// End of all domain details

			if ($result->pre_confidence_value==0){
				$response_array = array('status' => 'success','type'=>0,'domain'=>$domain, 'remaining'=> $num_task,'remaining_domains' => $num_domain);
			}
			else if(sizeof($task)==0 && $result->post_confidence_value==0){
				$response_array = array('status' => 'success','type'=>1,'domain'=>$domain, 'remaining'=> $num_task,'remaining_domains' => $num_domain);
			}
			else if(sizeof($task) > 0 && $result->post_confidence_value == 0)
			{
				// Select a particular task
				$response_array = task_select($domain_id, $userId, $task, $num_task);
			}
			else
			{
				$response_array = array("status"=>"fail");
			}
		}
		else
		{
			$response_array = array("status"=>"fail");
		}
	}	
	return $response_array;	
}

?>