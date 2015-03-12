<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model{

	protected $table = 'users';

	public static function boot()
	{
	  parent::boot();

	  Client::creating(function($client)
	  {
	      //
			  $client->experimental_condition = ((rand(0,1) == 0)? 'social' : 'control');
	  });
	}

	// public function save()
 //   {
 //      // before save code 
	//     $this->experimental_condition = ((rand(0,1) == 0)? 'social' : 'control');

 //      parent::save();
 //      // after save code
 //   }

}
