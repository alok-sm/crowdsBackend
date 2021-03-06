<?php namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BrowserState extends Controller {
	public function store()
	{
		$key = \Request::input('key');
		$val = \Request::input('val');

		DB::statement("REPLACE INTO browser_state VALUES ('$key','$val')");

		return array('success' => TRUE);
	}

	public function show()
	{
		$key = \Request::input('key');

		$browser_state = DB::table('browser_state')->select('value')->where("key", "=", $key)->get();

		foreach($browser_state as $state)
		{
			return array('success' => TRUE, 'data' => $state->value);
		}

		return array('success' => FALSE);
	}
}
