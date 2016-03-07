<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Answer;
use App\TaskBuffer;
use App\Statistic;
use App\Client;
use App\Http\Requests;
use Illuminate\Cookie\CookieJar;
use App\Http\Controllers\Controller;

class AnswerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	public function store()
	{
		$token = \Request::input('token');

		if ($this->check_not_user($token))
			return \Response::json(['status' => 'fail'], 200);

		$task_id = \Request::input('task_id');
		$user_id = Client::where('token', '=', $token)->first()->id;

		if (isset($task_id))
			return $this->handle_task_answer($task_id, \Request::input('data'), \Request::input('confidence'), $user_id);
		else
			return $this->handle_domain_answer(\Request::input('domain_id'), \Request::input('rank'), $user_id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	protected function add_stats($answer)
	{
		$stat = Statistic::where("task_id", $answer->task_id)->first();
  		if($stat == null) {
  			$stat = new Statistic;
  			$stat->task_id = $answer->task_id;
  		}
		$task_answers = \DB::table('answers')->where('answers.task_id', $stat->task_id)->whereNotIn('answers.data', ['null', 'timeout'])->orderBy(\DB::raw("cast(data as unsigned)"), 'asc')->lists('data');
		$count = sizeof($task_answers);
		if ($count != 0) {
			$stat->median = ($task_answers[floor(($count+1)/2) - 1] + $task_answers[floor(($count+2)/2) -1])/2;
		}
		else {
			$stat->median = $answer->points;
		}
		$stat->save();
	}

	protected function handle_task_answer($task_id, $data, $confidence, $user_id)
	{
		if ($task_id == null || $data == null || $user_id == null || $confidence == null)
			return \Response::json(array('status' => 'fail'), 200);

		$answer = Answer::where('task_id', '=', $task_id)->where('user_id', '=', $user_id)->first();
		if (isset($answer))
		{
			if ($answer->data == "null" && $answer->created_at->diffInSeconds() <= 45){
				$answer->data = $data;
				$answer->submitted_at = Carbon::now();
				$answer->confidence = $confidence;
			}
			else{
				$response_array = array('status' => 'fail');
				return \Response::json($response_array, 200);
			}

			$answer->ignore_save_condition = true;
			if ($answer->save()) {
				if (strcmp($answer->task->answer_type, "int") == 0) {
					$this->add_stats($answer);
				}
				$response_array = array('status' => 'success');
			}
			else
				$response_array = array('status' => 'fail');
		}
		else
			$response_array = array('status' => 'fail');

		return \Response::json($response_array, 200);
	}

	protected function check_not_user($token)
	{
		$user = Client::where('token', '=', (string) $token)->first();
		if ($user == null)
			return true;
		else
			return false;
	}

	protected function handle_domain_answer($domain_id, $rank, $user_id)
	{
		if ($rank == null || $domain_id == null || $user_id == null)
			return \Response::json(['status' => 'fail'], 200);

		$task_buffer = TaskBuffer::where('user_id', $user_id)->orderBy('id', 'desc')->first();
		//var_dump($task_buffer->domain()->first()->tasks());
		if (count($task_buffer->task_id_list) == $task_buffer->domain()->first()->tasks()->count() && $task_buffer->pre_confidence_value == null)
		{
			$task_buffer->pre_confidence_value = $rank;
			if($task_buffer->save())
				return \Response::json(['status' => 'success'], 200);
			else
				return \Response::json(['status' => 'fail'], 200);
		}
		else
			return \Response::json(['status' => 'fail'], 200);
	}
}
