<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model{

	protected $table = 'users';

	public function answers()
	{
		return $this->hasMany('App\Answer');
	}

	public static function boot()
	{
	  parent::boot();

	  Client::saving(function($client)
	  {
			$client->experimental_condition = ((rand(0,1) == 0)? 'social' : 'control');
	  });

	  Client::creating(function($client)
	  {
	      //
			$client->experimental_condition = ((rand(0,1) == 0)? 'social' : 'control');
	  });
	}

	// public static function save()
 //   {
 //      // before save code 
	//     $this->experimental_condition = ((rand(0,1) == 0)? 'social' : 'control');

 //      parent::save();
 //      // after save code
 //   }

	public function task_buffers()
	{
		return $this->hasMany('App\TaskBuffer', 'user_id');
	}

}
