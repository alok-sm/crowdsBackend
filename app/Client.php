<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model{

	protected $table = 'users';

	public function answers()
	{
		return $this->hasMany('App\Answer', 'user_id');
	}

	public function soc_read()
	{
		//$soc = DB::select(DB::raw('select social_id from social_counts where count = (select min(count) from social_counts);'));
		//return $soc->social_id;
		$lockfile = 'soc.lock';
		$lock = fopen($lockfile, 'a');

		$ret = flock($lock, LOCK_EX);
		$myfile = fopen("/var/www/crowds/crowds/app/social_condition_state.txt", "r");
		$ret_val = (intval(fread($myfile,filesize("/var/www/crowds/crowds/app/social_condition_state.txt")))+1)%4;
		fclose($myfile);
		$myfile = fopen("/var/www/crowds/crowds/app/social_condition_state.txt","w");
		fwrite($myfile,"$ret_val");
		fclose($myfile);
		$ret = flock($lock, LOCK_UN);
		fclose($lock);
		return $ret_val;
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

			$client->status = $client->soc_read();
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
