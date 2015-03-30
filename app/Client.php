<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model{

	protected $table = 'users';

	public function answers()
	{
		return $this->hasMany('App\Answer', 'user_id');
	}

	public static function boot()
	{
	  parent::boot();

	  Client::saving(function($client)
	  {
	  		$client->token = $client->generateRandomString(16);
			$client->experimental_condition = ((rand(0,1) == 0)? 'social' : 'control');
			if ($client->experimental_condition == 'social')
				$client->status = rand(1, 4);
	  });

	  Client::creating(function($client)
	  {
	  		$client->token = $client->generateRandomString(16);
			$client->experimental_condition = ((rand(0,1) == 0)? 'social' : 'control');
			if ($client->experimental_condition == 'social')
				$client->status = rand(1, 4);
	  });
	}

	// public static function save()
 //   {
 //      // before save code 
	//     $this->experimental_condition = ((rand(0,1) == 0)? 'social' : 'control');

 //      parent::save();
 //      // after save code
 //   }

	public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function task_buffers()
	{
		return $this->hasMany('App\TaskBuffer', 'user_id');
	}
}
