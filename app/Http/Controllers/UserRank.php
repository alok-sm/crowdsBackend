<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserRank extends Controller {
	public function store($user_id, $domain_id, $rank)
	{
		$user_id = \Request::input('user_id');
		$domain_id = \Request::input('domain_id');
		$rank = \Request::input('rank');
		
	}
}
