<?php namespace App\Http\Controllers;

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
			$task_type = Domain::find($domain_id)->tasks->first()->answer_type;
			$comparator = (strcmp($task_type, "int") == 0)? '<' : '>';
			if (isset($task_buffer))
			{
				$rank = TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->where('points', $comparator, $task_buffer->points)->count();
				$total_users = TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->count();
			}
			$domain_done = Client::find($user_id)->task_buffers()->count();
			$total_domains = Domain::all()->count();
			$remaining_domains = $total_domains - $domain_done;
			$response_array = array("status" => "success", "remaining_domains" => $remaining_domains, "total_domains" => $total_domains, "rank" => $rank + 1, "total_users" => $total_users, "points" => $task_buffer->points, "completion_code" => $task_buffer->completion_code);
		}
		else
		{
			$response_array = array("status" => "fail");
		}
		return \Response::json($response_array, 200);
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
