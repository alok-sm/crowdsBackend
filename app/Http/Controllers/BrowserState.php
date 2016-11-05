<?php namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BrowserState extends Controller {
	public function store($key, $val)
	{
		$browser_state = DB::table('browser_state');
		$browser_state::firstOrCreate(['key' => $key, 'val' => $val]);
		return array('success' => TRUE);
	}

	public function show($key)
	{
		$browser_state = DB::table('browser_state')->select('val')->where("key", "=", $key)->get();

		foreach($browser_state as $state)
		{
			return array('success' => TRUE, 'data' => $state->val);
		}

		return arrray('success' => FALSE);
	}
}
