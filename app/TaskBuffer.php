<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use App\Answer;

class TaskBuffer extends Model {

	use ValidatingTrait;

	protected $table = "task_buffers";

	protected $rules = ['user_id' => 'required','domain_id' => 'required','task_id_list' => 'required'];

	protected $casts = [
    	'task_id_list' => 'array'
	];
	
	protected $attributes =[
		'user_id' => '', 'domain_id' => '', 'task_id_list' => ''
	];

	public function validate_multiple_uniqueness($parameters)
	{
		// Get table name from first parameter
	    $table = array_shift($parameters);

	    // Build the query
	    $query = \DB::table($table);

	    foreach ($parameters as $i => $field){
			if (isset($this->id))
				$query->where($field, (int) $this->attributes[$parameters[$i]])->where("id", "!=", (int) $this->id);
			else
				$query->where($field, (int) $this->attributes[$parameters[$i]]);
	    }

	    // Validation result will be false if any rows match the combination
	    return ($query->count() == 0);
	}
	
	public function __construct() {
		parent::__construct();
	}

	public function domain()
	{
		return $this->belongsTo('App\Domain');
	}

	public function client()
	{
		return $this->belongsTo('App\Client', 'user_id', 'id');
	}

	public static function boot()
	{
		parent::boot();
		TaskBuffer::saving(function($task_buffer)
		{
			if ($task_buffer->validate_multiple_uniqueness(array('task_buffers','domain_id','user_id')) != 1)
				return false;
			if ($task_buffer->task_id_list == [])
			{
				// Calculate the score
				$task_buffer->points = Answer::where('user_id', $task_buffer->user_id)->whereIn('task_id', $task_buffer->domain->tasks->lists('id'))->sum('points');
			}
			return true;
		});
	}
}
