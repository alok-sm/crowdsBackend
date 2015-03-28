<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Answer extends Model {

	use ValidatingTrait;
	protected $table = 'answers';

	// protected $observables = ['validating', 'validated'];
	// //
	protected $rules = ['user_id' => 'required','task_id' => 'required','data' => 'required','time_taken' => "required", 'confidence' => "required"];

	protected $attributes =[
		'user_id' => '', 'task_id' => '', 'data' => '', 'time_taken' => '', 'confidence' => ''
	];

	// public function __construct() {
	// 	\Validator::extend('unique_multiple', function ($attribute, $value, $parameters)
	// 	{
	// 	    // Get table name from first parameter
	// 	    $table = array_shift($parameters);

	// 	    // Build the query
	// 	    $query = \DB::table($table);

	// 	    // echo "ID IN NEXT LINE\n";
	// 	    // echo $this->id;

	// 	    foreach ($parameters as $i => $field){
	// 	    	echo "Attribute = ". $this->attributes[$field] ."<br />";
	// 	    	echo "TASK ID = ". $this->task_id ."<br />";
	// 	    	var_dump($this);
	// 	    	echo "FIELD = ". $field . " its value = ". $this->attributes[$field] . "<br />";
	// 			$query->where($field, (int) $this->attributes[$parameters[$i]]);
	// 	    }

	// 	   // echo "COUNT = ". ($query->count());
	// 	    // Validation result will be false if any rows match the combination
	// 	    return ($query->count() == 0);
	// 	});
	// 	parent::__construct();
	// }

	public function task()
	{
		return $this->belongsTo('App\Task');
	}

	public function user()
	{
		return $this->belongsTo('App\Client');
	}

	public function validate_multiple_uniqueness($parameters)
	{
		// Get table name from first parameter
	    $table = array_shift($parameters);

	    // Build the query
	    $query = \DB::table($table);

	    // echo "ID IN NEXT LINE\n";
	    // echo $this->id;

	    foreach ($parameters as $i => $field){
			$query->where($field, (int) $this->attributes[$parameters[$i]]);
	    }

	   // echo "COUNT = ". ($query->count());
	    // Validation result will be false if any rows match the combination
	    return ($query->count() == 0);
	}

	public static function boot()
	{
	  parent::boot();
	  Answer::saving(function($answer)
	  {
	  	if ($answer->validate_multiple_uniqueness(array('answers','task_id','user_id')) != 1)
	  		return false;
	  	$domain = TaskBuffer::where('user_id', $answer->user_id)->orderBy('id', 'desc')->first();
		  if ($domain != null && count($domain->task_id_list) > 0)
		  {
			$array = $domain->task_id_list;
				$index=array_search($answer->task_id,$array);
				array_splice($array,$index,1);
				$domain->task_id_list = $array;
				if ($domain->save())
					return true;
				else{
					return false;
				}
		  }
		  else
		  {
			// $task_buffer_exists = TaskBuffer::where("user_id", $answer->user_id)->where("domain_id", $answer->task()->first()->domain_id)->first();
			// if (!isset($task_buffer_exists)){
			// 	$tasks_buffer = new TaskBuffer();
			// 	$tasks_buffer->user_id = $answer->user_id;
			// 	$tasks_buffer->domain_id = $answer->task()->first()->domain_id;
			// 	$array_value = $answer->task()->first()->domain()->first()->tasks()->lists('id');
			// 	if (($key = array_search((string) $answer->task_id, $array_value)) !== false)
			// 		unset($array_value[$key]);
			// 	$tasks_buffer->task_id_list = $array_value;
			// 	$tasks_buffer->save();
			// }
			// else
				return false;
		  }
		  return true;
	  });
	}

	// public static function boot()
	// {
	// 	parent::boot();

	// 	\Event::listen('eloquent.validated.passed', function($answer, $event)
	// 	{
	// 	  echo "EXECuting SAVING FROM ANSWER\n";
	//       // Check if it belongs to correct domain or not; 
	// 	  $domain = TaskBuffer::where('user_id', '=' , $userId)->last();

	// 	  if ($domain != null && $domain->task_id_list != [])
	// 	  {
	// 	  	echo "INSIDE";
	// 	  	if (($key = array_search($answer->task_id, $domain->task_id_list)) !== false) {
	// 		    unset($domain->task_id_list[$key]);
	// 		    if ($domain->save())
	// 		    {
	// 		    	echo "DOMAIN IS SAVE!";
	// 		    }
	// 		    else
	// 		    	echo "diugiudfgiuf";
	// 		}
	// 		else
	// 		{
	// 			return false;
	// 		}
	// 	  }
	// 	  else
	// 	  {
	// 	  	$tasks_buffer = new TaskBuffer();
	// 	  	$tasks_buffer->user_id = $answer->user_id;
	// 	  	$tasks_buffer->domain_id = $answer->task()->domain()->id;
	// 	  	echo "HBiufhdiuhf";
	// 	  	$tasks_buffer->save();
	// 	  }
	// 	  return true;
	//   });
	// }

}