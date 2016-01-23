<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use App\Statistic;

class Answer extends Model {

	use ValidatingTrait;
	protected $table = 'answers';

	public $ignore_save_condition = false;
	// protected $observables = ['validating', 'validated'];
	// //
	protected $rules = ['user_id' => 'required','task_id' => 'required','data' => 'required','confidence' => 'required'];

	protected $attributes =[
		'user_id' => '', 'task_id' => '', 'data' => '', 'confidence' => '', 'submitted_at' => ''
	];
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

	    foreach ($parameters as $i => $field){
			$query->where($field, (int) $this->attributes[$parameters[$i]]);
	    }

	    // Validation result will be false if any rows match the combination
	    return ($query->count() == 0);
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

	public static function boot()
	{
	  parent::boot();
	  Answer::saving(function($answer)
	  {
	  	if (strcmp($answer->data, $answer->task->correct_answer) == 0 && strcmp($answer->task->answer_type, "mcq") == 0)
	  		$answer->points = 1;
	  	else if(strcmp($answer->task->answer_type, "int") == 0) {
	  		$answer->points = abs($answer->task->correct_answer - $answer->data);
	  	}
	  	else 
	  		$answer->points = 0;
		if (!($answer->ignore_save_condition))
		{
			if ($answer->validate_multiple_uniqueness(array('answers', 'task_id', 'user_id')) != 1)
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
				return false;
			  }
			  return true;
		}
	  });
	}
}	