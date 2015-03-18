<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

	protected $rules = ['title' => 'required', 'type' => 'required', 'data' => 'required', 'answer_type' => 'required', 'answer_data' => 'required', 'correct_answer' => 'required'];

	//
	public function domain()
	{
		return $this->belongsTo('App\Domain');
	}

	//
	public function answers()
	{
		return $this->hasMany('App\Domain');
	}
}
