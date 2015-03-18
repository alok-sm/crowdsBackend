<?php

return array(
	'title' => 'User',
	'single' => 'User',
	'model' => 'App\Client',
	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'age',
		'gender',
		'country'
		),
	/**
	 * The filter set
	 */
	'filters' => array(
		'id',
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