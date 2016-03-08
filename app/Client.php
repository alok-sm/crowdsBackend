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
	  		if ($client->token == '') {
	  			$client->token = $client->generateRandomString(16);
	  			$client->is_mturk = false;
	  		}
	  		else {
	  			$client->token = 'mturk-' . $client->token;
	  			$client->is_mturk = true;
	  		}

			$client->status = rand(0, 3);
			if ($client->status == 3) 
				$client->status = 4;
	  	});
	}

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
