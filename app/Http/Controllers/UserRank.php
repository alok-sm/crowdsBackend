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
		$assignment_id = \Request::input('assignment_id');
		$hit_id = \Request::input('hit_id');
		
		DB::statement("INSERT INTO user_domain_rank VALUES ('$user_id','$domain_id','$rank','$assignment_id','$hit_id')");

		return array('success' => TRUE);
	}
}
