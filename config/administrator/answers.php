<?php

return array(
	'title' => 'Answer',
	'single' => 'Answer',
	'model' => 'App\Answer',
	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'task_id',
		'user_id',
		
		'answer_data' => array(
			'title' => 'Answer Data',
			'select' => "data",
		),
		'confidence',
		'created_at',
		'submitted_at'
	),
	/**
	 * The filter set
	 */
	'filters' => array(
		'id',
		'task_id' => array(
			'title' => 'Task Id',
		),
		'user_id' => array(
			'title' => 'User Id',
		),
		'answer_data' => array(
			'title' => 'Answer Data',
		),
	),
		/**
	 * The edit fields
	 */
	'edit_fields' => array(
		
		'created_at' => array(
			'title' => "This field is not needed so it's not working",
			'type' => 'text',
		)
		
	),

);