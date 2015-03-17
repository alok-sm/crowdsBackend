<?php namespace App\Http\Controllers;

use App\Answer;
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
		$answer = new Answer;
		$answer->task_id = \Request::input('task_id');
		$answer->data = \Request::input('data');
		$user_id = \Request::cookie('crowd_id');
		
		echo $user_id;
		$user = Client::find($user_id);
		if ($user == null)
		{
			$contents = ['status' => 'failure'];
			return \Response::json($contents, 200);
		}
		$answer->user_id = $user_id;
		$answer->time_taken = \Request::input('time_taken');
		if ($answer->save()){
			$response_array = array('status' => 'success');
		}
		else
		{
			$response_array = array('status' => 'fail');
		}

		$contents = ['status' => 'success'];
		return \Response::json($contents, 200);
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

}
