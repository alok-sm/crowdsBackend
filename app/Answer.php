<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Answer extends Model {

	use ValidatingTrait;
	protected $table = 'answers';

	public $ignore_save_condition = false;
	// protected $observables = ['validating', 'validated'];
	// //
	protected $rules = ['user_id' => 'required','task_id' => 'required','time_taken' => "required"];

	protected $attributes =[
		'user_id' => '', 'task_id' => '', 'time_taken' => ''];

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

	public static function boot()
	{
	  parent::boot();
	  Answer::saving(function($answer)
	  {
		if (!($answer->ignore_save_condition))
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
				return false;
			  }
			  return true;
		}
	  });
	}
}