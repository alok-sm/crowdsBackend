<?php namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserRank extends Controller {
	public function store()
	{
		$user_id = \Request::input('user_id');
		$domain_id = \Request::input('domain_id');
		$rank = \Request::input('rank');
		
		DB::statement("INSERT INTO user_domain_rank VALUES ('$user_id','$domain_id','$rank')");

		return array('success' => TRUE);
	}
}
