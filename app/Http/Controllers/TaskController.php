<?php namespace App\Http\Controllers;

use DB;
use App\Client;
use App\TaskBuffer;
use App\Domain;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TaskController extends Controller {

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

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		
		
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
	
	public function assign()
	{
		
		$token= \Request::input('token');

		$status = Client::where('token', '=', $token)->first();

		if ($status != null)
			$response_array = helper($status->id);
		else
			$response_array = array("status" => "fail");

		return \Response::json($response_array, 200);
	}

	public function rank()
	{
		$token= \Request::input('token');
		$status = Client::where('token', '=', $token)->first();
		$rank = null;
		$total_users = null;

		if ($status != null)
		{
			$user_id = $status->id;
			$task_buffer = TaskBuffer::where('user_id', $user_id)->where('task_id_list', '[]')->orderBy('id','desc')->first();
			$task_type = Domain::find($task_buffer->domain_id)->tasks->first()->answer_type;
			$comparator = (strcmp($task_type, "int") == 0)? '<' : '>';
			if (isset($task_buffer))
			{
				$rank = TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->where('points', $comparator, $task_buffer->points)->count();
				$total_users = TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->count();
			}
			$domain_done = Client::find($user_id)->task_buffers()->count();
			$total_domains = Domain::all()->count();
			$remaining_domains = $total_domains - $domain_done;
			$points = 0;
			if (strcmp($task_type, "int") == 0) {
				$crowd_points = calculate_int_points($task_buffer->domain_id);
				$crowd_rank = TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->where('points', '<', $crowd_points)->count();
				$points = Answer::where('user_id', $user_id)->whereIn('task_id', Task::where('domain_id', $task_buffer->domain_id)->lists('id'))->where('points', 0)->count();
			}
			else {
				$crowd_points = calculate_mcq_points($task_buffer->domain_id);
				$crowd_rank = TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->where('points', '>', $crowd_points)->count();
				$points = $task_buffer->points;
			}

			$response_array = array("status" => "success", "remaining_domains" => $remaining_domains, "total_domains" => $total_domains, "rank" => $rank + 1, "total_users" => $total_users, "points" => $points, "completion_code" => $task_buffer->completion_code, "crowd_rank" => $crowd_rank + 1);
		}
		else
		{
			$response_array = array("status" => "fail");
		}
		return \Response::json($response_array, 200);
	}

	private function calculate_mcq_points($domain_id) {
		$task_object = DB::select('select a.task_id, a.data, count(a.data) as count, t.correct_answer from tasks t, answers a, domains d where d.id = t.domain_id and t.id = a.task_id and t.domain_id = ? and a.data != "null" and a.data != "timeout" group by a.task_id, a.data', [$domain_id]);
		$points = 0;
		$task_id = 0;
		$max = 0; // This needs to be initialized
		$correct_answer_points = -1;
		foreach ($task_object as $object) {
			if ($task_id != intval($object->task_id)) {
				if ($correct_answer_points >= $max) {
					$points += 1;
					var_dump($task_id);
				}
				$max = 0;
				$task_id = intval($object->task_id);
			}
			if (strcmp($object->data, $object->correct_answer) == 0)
				$correct_answer_points = intval($object->count);
			if (intval($object->count) > $max)
				$max = intval($object->count);
		}
		return $points;
	}

	private function calculate_int_points($domain_id) {
		$task_object = DB::select('select avg(a.data) as average, t.correct_answer as correct_answer, t.id as task_id from tasks t, answers a, domains d where d.id = t.domain_id and t.id = a.task_id and t.domain_id = ? and a.data != "null" and a.data != "timeout" group by a.task_id', [$domain_id]);
		$points = 0;
		foreach ($task_object as $object) {
			$points += abs($object->correct_answer - $object->average)
		}
		return $points;
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

}
