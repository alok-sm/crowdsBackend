<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model {

	//
	public function tasks()
	{
		return $this->hasMany('App\Task');
	}

	public function task_buffers()
	{
		return $this->hasMany('App\TaskBuffer');
	}
}
