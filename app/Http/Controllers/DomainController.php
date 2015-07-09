<?php namespace App\Http\Controllers;

use App\TaskBuffer;
use Illuminate\Cookie\CookieJar;
use App\Http\Controllers\Controller;

class DomainController extends Controller {

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
		//
		$user_id= \Request::cookie('crowd_id');
		$domain = TaskBuffer::where('user_id', $user_id)->orderBy('id', 'desc')->first();
		if ($domain != null && count($domain->task_id_list) > 0)
		{
		   	$array = $domain->task_id_list;
		  	if (($key = array_search($answer->task_id, $array)) !== false) {
			    unset($array[$key]);
			    $domain->task_id_list = $array;
			    if ($domain->save())
			    	return true;
			    else{
			    	return false;
				}
			}
			else
			{
				return false;
			}



		$client->age = \Request::input('age');
		$client->gender = \Request::input('gender');
		$client->country = \Request::input('country');
		
		if ($client->save()){
			$response_array = array('status' => 'success');
		}
		else
		{
			$response_array = array('status' => 'fail');
		}
		
		// echo \Cookie::get('client_id');

		// echo "Cookie to be set in next line";
		// echo $client->remember_token;

		return \Response::json($response_array, 200)->withCookie(cookie()->forever('crowd_id', $client->id));
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
			if (isset($task_buffer))
			{
				$rank = TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->where('points', '<', $task_buffer->points)->count();
				$total_users = TaskBuffer::where('domain_id', $task_buffer->domain_id)->where('task_id_list', '[]')->count())
			}
			$domain_done = Client::find($user_id)->task_buffers()->count();
			$total_domains = Domain::all()->count();
			$remaining_domains = $total_domains - $domain_done + 1;
			return {"status" => "success", "remaining_domains" => $remaining_domains, "total_domains" => $total_domains, "rank" => $rank, "total_users" => $total_users, "points" => $task_buffer->points}
		}
		else
			return {"status" => "fail"}
	}

}
