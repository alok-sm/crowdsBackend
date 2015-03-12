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
		$client->age = \Request::input('age');
		$client->gender = \Request::input('gender');
		$client->country = \Request::input('country');
		$client->save();
		
		// echo \Cookie::get('client_id');

		// echo "Cookie to be set in next line";
		// echo $client->remember_token;

		return \Response::json(array(
        'error' => false,),
        200
    )->withCookie(cookie()->forever('crowd_id', $client->id));
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
