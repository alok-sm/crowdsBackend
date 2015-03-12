<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

	//
	public function domain()
	{
		return $this->belongsTo('App\Domain');
	}
}
