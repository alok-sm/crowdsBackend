<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Answer extends Model {

	use ValidatingTrait;

	//
	protected $rules = ['user_id' => 'required|unique_multiple:answers,task_id,user_id','task_id' => 'required','data' => 'required','time_taken' => "required"];

	protected $attributes =[
		'user_id' => '', 'task_id' => '', 'data' => '', 'time_taken' => ''
	];

	public static function boot()
	{
	  parent::boot();

	  Answer::saving(function($answer)
	  {
	      // Check if it belongs to correct domain or not; 
			
	  });
	}

	
	public function __construct() {
		\Validator::extend('unique_multiple', function ($attribute, $value, $parameters)
		{
		    // Get table name from first parameter
		    $table = array_shift($parameters);

		    // Build the query
		    $query = \DB::table($table);

		    foreach ($parameters as $i => $field){
		        $query->where($field, $this->attributes[$parameters[$i]]);
		    }

		    // Validation result will be false if any rows match the combination
		    return ($query->count() == 0);
		});
		parent::__construct();
	}

	public function task()
	{
		return $this->belongsTo('App\Task');
	}

	public function user()
	{
		return $this->belongsTo('App\Client');
	}

}
