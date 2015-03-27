<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveConfidenceScoreToTaskBuffers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('answers', function($table)
		{
			$table->dropColumn('pre_confidence_value');
		});

		Schema::table('answers', function($table)
		{
			$table->dropColumn('post_confidence_value');
		});
		
		Schema::table('task_buffers', function($table)
		{
			$table->string('pre_confidence_value')->default('');
			$table->string('post_confidence_value')->default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		
		Schema::table('answers', function($table)
		{
			$table->string('pre_confidence_value');
			$table->string('post_confidence_value');
		});
		

		Schema::table('task_buffers', function($table)
		{
			$table->dropColumn('pre_confidence_value');
		});
		
		Schema::table('task_buffers', function($table)
		{
			// $table->dropColumn('pre_confidence_value');
			$table->dropColumn('post_confidence_value');
		});

		
	}

}
