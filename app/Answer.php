<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model {

	//
	public function task()
	{
		return $this->belongsTo('App\Task');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
