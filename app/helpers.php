<?php
use Carbon\Carbon;
use App\TaskBuffer;
use App\Client;
use App\Task;
use App\Domain;
use App\Answer;
use Illuminate\Support\Facades\DB;

function array_median($array) {
  // perhaps all non numeric values should filtered out of $array here?
  $iCount = count($array);
  // if we're down here it must mean $array
  // has at least 1 item in the array.
  $middle_index = floor($iCount / 2);
  sort($array, SORT_NUMERIC);
  $median = $array[$middle_index]; // assume an odd # of items
  // Handle the even case by averaging the middle 2 items
  if ($iCount % 2 == 0) {
    $median = ($median + $array[$middle_index - 1]) / 2;
  }
  return $median;
}


function submission($task_id, $user_status){
	$response= DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.is_mturk', true)->where('users.status', $user_status)->where('answers.task_id',$task_id)->whereNotIn('answers.data', ['null', 'timeout'])->lists('data');
	$answer = DB::table('tasks')->where('id',$task_id)->select('answer_type')->first();
	$answer_type = $answer->answer_type;
	$total = sizeof($response);

	if(sizeof($response) < 3 && $answer_type == "int")
		$response="Not enough data";

	else if($answer_type == "mcq"){
		$response = DB::table('answers')->select('answers.data as data', DB::raw("count('answers.data') as total"))->join('users', 'users.id', '=', 'answers.user_id')->where('users.is_mturk', true)->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->groupBy('data')->get();
		$hist = [];
		foreach( $response as $item )
			$hist[$item->data] = $item->total;
		arsort($hist);
		$response = array_slice($hist,0,3,true);
	}

	else if($answer_type == "int"){
		//$data = DB::table('answers')->select('answers.data as data', DB::raw("count('answers.data') as total"))->join('users', 'users.id', '=', 'answers.user_id')->where('users.is_mturk', true)->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->get();
 		$data = array_map('intval', DB::table('answers')->select('answers.data as data')->join('users', 'users.id', '=', 'answers.user_id')->where('users.is_mturk', true)->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id', $task_id)->lists('data'));
		$med = array_median($data);
		// if ($total % 2 != 0)
		// 	$total += 1;
		// $median = (int)(ceil($total / 2));
		// $upper_index = (int)(ceil($total / 4) - 1);
		// $lower_index = (int)(ceil($total * 3 / 4) - 1);

		// $median = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.is_mturk', true)->where('users.status', $user_status)->whereNotIn('answers.data', ['null', 'timeout'])->where('answers.task_id', $task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"), 'asc')->skip($median)->limit(1)->first();
		// $median_up = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.is_mturk', true)->where('users.status', $user_status)->whereNotIn('answers.data', ['null', 'timeout'])->where('answers.task_id', $task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"), 'asc')->skip($upper_index)->limit(1)->first();
		// $median_down = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.is_mturk', true)->where('users.status', $user_status)->whereNotIn('answers.data', ['null', 'timeout'])->where('answers.task_id', $task_id)->orderBy(DB::raw("cast(answers.data as unsigned)"), 'asc')->skip($lower_index)->limit(1)->first();

		// $iqr = ($median_down->data - $median_up->data);
		// $median = $median->data;

		//$response = array('count'=>$total, 'median'=>$median, 'first_quartile'=>$median_up, 'third_quartile' =>$median_down );
		$response = array('data' => $med);
	}
	return $response;
}

function first_submission($task_id, $user_status){
	$response= DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->limit(3)->lists('data');
	// if(sizeof($response)<5 && $answer_type == "int")
	// 	$response="Not enough data";
	return $response;
}

function recent_submission($task_id, $user_status){
	$response = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.is_mturk', true)->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->orderBy('answers.id','desc')->limit(3)->lists('data');
	// if(sizeof($response)<5 && $answer_type == "int")
	// 	$response="Not enough data";
	return $response;
}

function confident_submission($task_id, $user_status){
	$response = DB::table('answers')->join('users', 'users.id', '=', 'answers.user_id')->where('users.is_mturk', true)->whereNotIn('answers.data', ['null', 'timeout'])->where('users.status', $user_status)->where('answers.task_id',$task_id)->orderBy('answers.confidence', 'desc')->limit(3)->lists('data');
	// if(sizeof($response)<5 && $answer_type == "int")
	// 	$response="Not enough data";
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
	$answer_data = $task->answer_data;
	// file_get_contents("https://requestb.in/189rqje1?status=initial&res=$answer_data");

	if (in_array($task_id, array("10240", "10241", "10242", "10243", "10244", "10245", "10246", "10247", "10248", "10249", "10250", "10251", "10252", "10253", "10254", "10255", "10256", "10257", "10258", "10259", "10280", "10281", "10282", "10283", "10284", "10285", "10286", "10287", "10288", "10289", "10290", "10291", "10292", "10293", "10294", "10295", "10296", "10297", "10298", "10299", "10340", "10341", "10342", "10343", "10344", "10345", "10346", "10347", "10348", "10349", "10350", "10351", "10352", "10353", "10354", "10355", "10356", "10357", "10358", "10359", "10360", "10361", "10362", "10363", "10364", "10365", "10366", "10367", "10368", "10369", "10370", "10371", "10372", "10373", "10374", "10375", "10376", "10377", "10378", "10379", "10400", "10401", "10402", "10403", "10404", "10405", "10406", "10407", "10408", "10409", "10410", "10411", "10412", "10413", "10414", "10415", "10416", "10417", "10418", "10419", "10460", "10461", "10462", "10463", "10464", "10465", "10466", "10467", "10468", "10469", "10470", "10471", "10472", "10473", "10474", "10475", "10476", "10477", "10478", "10479", "10500", "10501", "10502", "10503", "10504", "10505", "10506", "10507", "10508", "10509", "10510", "10511", "10512", "10513", "10514", "10515", "10516", "10517", "10518", "10519", "10520", "10521", "10522", "10523", "10524", "10525", "10526", "10527", "10528", "10529", "10530", "10531", "10532", "10533", "10534", "10535", "10536", "10537", "10538", "10539", "10620", "10621", "10622", "10623", "10624", "10625", "10626", "10627", "10628", "10629", "10630", "10631", "10632", "10633", "10634", "10635", "10636", "10637", "10638", "10639", "10740", "10741", "10742", "10743", "10744", "10745", "10746", "10747", "10748", "10749", "10750", "10751", "10752", "10753", "10754", "10755", "10756", "10757", "10758", "10759", "10820", "10821", "10822", "10823", "10824", "10825", "10826", "10827", "10828", "10829", "10830", "10831", "10832", "10833", "10834", "10835", "10836", "10837", "10838", "10839", "10860", "10861", "10862", "10863", "10864", "10865", "10866", "10867", "10868", "10869", "10870", "10871", "10872", "10873", "10874", "10875", "10876", "10877", "10878", "10879", "10900", "10901", "10902", "10903", "10904", "10905", "10906", "10907", "10908", "10909", "10910", "10911", "10912", "10913", "10914", "10915", "10916", "10917", "10918", "10919", "10020", "10021", "10022", "10023", "10024", "10025", "10026", "10027", "10028", "10029", "10030", "10031", "10032", "10033", "10034", "10035", "10036", "10037", "10038", "10039", "10040", "10041", "10042", "10043", "10044", "10045", "10046", "10047", "10048", "10049", "10050", "10051", "10052", "10053", "10054", "10055", "10056", "10057", "10058", "10059", "10080", "10081", "10082", "10083", "10084", "10085", "10086", "10087", "10088", "10089", "10090", "10091", "10092", "10093", "10094", "10095", "10096", "10097", "10098", "10099", "10160", "10161", "10162", "10163", "10164", "10165", "10166", "10167", "10168", "10169", "10170", "10171", "10172", "10173", "10174", "10175", "10176", "10177", "10178", "10179", "10180", "10181", "10182", "10183", "10184", "10185", "10186", "10187", "10188", "10189", "10190", "10191", "10192", "10193", "10194", "10195", "10196", "10197", "10198", "10199", "10200", "10201", "10202", "10203", "10204", "10205", "10206", "10207", "10208", "10209", "10210", "10211", "10212", "10213", "10214", "10215", "10216", "10217", "10218", "10219", "10220", "10221", "10222", "10223", "10224", "10225", "10226", "10227", "10228", "10229", "10230", "10231", "10232", "10233", "10234", "10235", "10236", "10237", "10238", "10239"))) {


		$data_arr = explode ("," , $answer_data);
		shuffle($data_arr);
		$answer_data = implode(",", $data_arr);

		// file_get_contents("https://requestb.in/189rqje1?status=true&res=".$answer_data);
	}
	// else{
	// 	file_get_contents("https://requestb.in/189rqje1?false");
	// }

	$task_json = array("id" => $task_id, "title" => $task->title, "type" => $task->type, "data" => $task->data, "answer_type" => $task->answer_type, "answer_data" => $answer_data, "units" => $task->units);
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
		/*else{
			$answer->data = "timeout";
			$answer->ignore_save_condition = true;
			$answer->save();
		}*/
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

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function create_task_buffer($domain_id, $user_id)
{
	$tasks = Task::where('domain_id', $domain_id)->lists('id');
	$tb = new TaskBuffer;
	$tb->domain_id = $domain_id;
	$tb->user_id = $user_id;
	$tb->task_id_list = $tasks;
	$tb->points = 0;
	$tb->completion_code = generateRandomString(25);
	return $tb->save();
}
function robin()
{

	$dom = DB::select(DB::raw('select * from domain_counts where count = (select min(count) from domain_counts)'));
	
	foreach ($dom as $di) {
		$ct = $di->count;
    	$ret_val = $di->domain_id;
    	break;
	}
	$ct = $ct + 1;
	$upd = DB::statement('update domain_counts set count='.$ct.' where domain_id='.$ret_val);
	// echo $ret_val;
	return $ret_val;
    /*$lockfile = 'dom_rob.lock';
    $lock = fopen($lockfile, 'a');
    $ret = flock($lock, LOCK_EX);
    $ret_val = file_get_contents("/var/www/crowds/crowds/app/domain_robin.json");
    $json_a = json_decode($ret_val, true);

	$min_hits = 99999999;
	$min_domain_id = 0;
	foreach($json_a as $key=>$val)
	{
		if(in_array($key,$domain_id_list))
		{
			if ($val < $min_hits)
			{
				$min_hits=$val;
				$min_domain_id=$key;
			}
		}
	}

    $json_a[$min_domain_id]=$json_a[$min_domain_id]+1;
    $json_str = json_encode($json_a);
    file_put_contents("/var/www/crowds/crowds/app/domain_robin.json",$json_str);
    $ret = flock($lock, LOCK_UN);
    fclose($lock);
    return $min_domain_id;*/
}
function select_domain($domain_id_list)
{

	// $size = sizeof($domain_id_list);
	// $index = robin(0, $size-1);
	$domain_id = robin();
	return $domain_id;
}

// Assigns domain to a user
function assign_random_domain($user_id)
{
	$user_domains = TaskBuffer::where('user_id', $user_id)->lists('domain_id');
	$all_domains = Domain::all()->lists('id');
	$diff = array_diff($all_domains, $user_domains);
	$diff = array_values($diff);

	if (sizeof($diff) > 0)
	{
		$domain_id = select_domain($diff);
		if (create_task_buffer($domain_id, $user_id))
			$response_array = array("status" => "success");
		else
			// echo "assigning random domain failed";
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
		// echo "Failed selecting task";
		return array("status" => "fail");
	}
}

function total_domains($user_id)
{
	return Domain::where('id', '!=' , 19)->count();
}

function remaining_domains($user_id)
{
	$domain_done = Client::find($user_id)->task_buffers()->count();
	$domain_total = total_domains($user_id);
	$num_domains = $domain_total-$domain_done;
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
		return TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->where('points', '<', $task_buffer->points)->count();
	}
	else
		return null;
}

function users($user_id)
{
	$task_buffer = TaskBuffer::where('user_id', $user_id)->where('task_id_list', '[]')->orderBy('id','desc')->first();
	if (isset($task_buffer))
		return (TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->count());
	else
		return 0;
}


function helper($userId)
{
	// Check if there is a task buffer
	$task_buffer = TaskBuffer::where('user_id', $userId)->orderBy('id','desc')->first();
	if (isset($task_buffer))
	{
		// Case 1: When the pre-confidence value is 0
		if ($task_buffer->pre_confidence_value == 0)
			$response_array = array('status' => 'success', 'type' => 0, "domain" => domain_json($task_buffer->domain_id), "remaining" => count($task_buffer->task_id_list), "remaining_domains" => remaining_domains($userId), "total_domains" => total_domains($userId));
		else if (count($task_buffer->task_id_list) != 0){
			// Case 2: When there are pending tasks
			$task = task_status($userId);
			if ($task == false)
			{
				// Case 2a: There is no pending task with respect to timer, hence assign new task
				$response_array = task_select($task_buffer->domain_id, $task_buffer->user_id, $task_buffer->task_id_list);
				// Store the response in answer
			}
			else
			{
				// Case 2b: There is a pending task with respect to time. hence return it.
				$response_array = $task;
			}
			$answer = Answer::where('user_id', $userId)->where('task_id', $response_array["task"]["id"])->first();
			$answer->server_response = json_encode($response_array);
			$answer->ignore_save_condition = true;
			$answer->save();
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
