<?php namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests;
use Illuminate\Cookie\CookieJar;
use App\Http\Controllers\Controller;

class ClientController extends Controller {

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
		$client = new Client;
		$client->age = \Request::input('age', '');
		$client->gender = \Request::input('gender', '');
		$client->education = \Request::input('education', '');
		$client->employment = \Request::input('employment', '');
		$client->mt = \Request::input('mturk', '');
		
		if($client->mt == "false")
		{
			if ($client->save()){
				$response_array = array('status' => 'success', 'token' => $client->token);
			}
			else
			{
				$response_array = array('status' => 'fail');
			}
		}
		else
		{
			try{
				$client->token = $client->mt
				$client->experimental_condition = ((rand(0,1) == 0)? 'social' : 'control');
				
				if ($client->experimental_condition == 'social')
					$client->status = rand(1, 4);
					
				$response_array = array('status' => 'success', 'token' => $client->token);
			}
			catch (Exception $e) {
				$response_array = array('status' => 'fail');
			}
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

}
