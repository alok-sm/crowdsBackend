<?php
/**
 * User model config
 */
return array(
	'title' => 'Task',
	'single' => 'task',
	'model' => 'App\Task',
	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'domain_id',
		'title' => array(
			'title' => 'Name/Description',
			'select' => "title",
		),
		'type' => array(
			'title' => 'Task Data Type',
			'select' => "type",
		),
		'data' => array(
			'title' => 'Path to Task Data',
			'select' => "data",
		),
		'answer_type' => array(
			'title' => 'Type of Answer Data',
			'select' => "answer_type",
		),
		'answer_data' => array(
			'title' => 'Answer Data',
			'select' => "answer_data",
		),
		'correct_answer' => array(
			'title' => 'Correct Answer',
			'select' => "correct_answer",
		),
	),
	/**
	 * The filter set
	 */
	'filters' => array(
		'id',
		'title' => array(
			'title' => 'Name',
		),
		'type' => array(
			'title' => 'Task Type',
		),
		'answer_type' => array(
			'title' => 'Answer Type',
		),
	),
	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'domain' => array(
			'title' => 'Domain',
			'type' => 'relationship',
			'name_field' => 'name',
		),
		'title' => array(
			'title' => 'Task Name/Description',
			'type' => 'text',
		),
		'type' => array(
			'title' => 'Type of Task Data',
			'type' => 'enum',
			'options' => array('text','image','audio','video'),
		),
		'image' => array(
			'title' => 'Image',
			'type' => 'image',
			'location' => public_path().'/uploads/original/',
			'naming' => 'random',
		
			'size_limit' => 2,
			 'sizes' => array(
				array(220, 138, 'landscape', public_path() . '/uploads/resize/', 100),
    )
    
		),
		'data' => array(
			'title' => 'Path to Task Data',
			'type' => 'text',
		),
		'answer_type' => array(
			'title' => 'Type of Answer Data',
			'type' => 'enum',
			'options' => array('integer','text','select','map'),
		),
		'answer_data' => array(
			'title' => 'Answer Data',
			'type' => 'text',
		),
		'correct_answer' => array(
			'title' => 'Correct Answer',
			'type' => 'text',
		),
		
		
	),
);