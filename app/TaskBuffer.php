<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class TaskBuffer extends Model {

	use ValidatingTrait;

	protected $table = "task_buffers";

	protected $rules = ['user_id' => 'required','domain_id' => 'required|unique_multiple:task_buffers,domain_id,user_id','task_id_list' => 'required'];

	protected $casts = [
    	'task_id_list' => 'array'
	];
	
	protected $attributes =[
		'user_id' => '', 'domain_id' => '', 'task_id_list' => ''
	];
	
	public function __construct() {
		\Validator::extend('unique_multiple', function ($attribute, $value, $parameters)
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
		});
		parent::__construct();
	}

	public function domain()
	{
		return $this->belongsTo('App\Domain');
	}

	public function user()
	{
		return $this->belongsTo('App\Client');
	}
}
