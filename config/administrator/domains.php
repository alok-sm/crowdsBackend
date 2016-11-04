<?php
/**
 * User model config
 */
return array(
	'title' => 'Domain',
	'single' => 'domain',
	'model' => 'App\Domain',
	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'name' => array(
			'title' => 'Name',
			'select' => "name",
		),
		'description' => array(
			'title' => 'Description',
			'select' => "description",
		),
	),
	/**
	 * The filter set
	 */
	'filters' => array(
		'name' => array(
			'title' => 'Name',
		),
	),
	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'name' => array(
			'title' => 'Name',
			'type' => 'text',
		),
		'description' => array(
			'title' => 'Description',
			'type' => 'text',
		),
	),
);