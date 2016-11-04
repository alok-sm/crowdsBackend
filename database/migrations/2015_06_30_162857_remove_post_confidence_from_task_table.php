<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePostConfidenceFromTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('task_buffers', function(Blueprint $table)
		{
			//
			$table->dropColumn('post_confidence_value');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('task_buffers', function(Blueprint $table)
		{
			//
			$table->integer('post_confidence_value');
		});
	}

}
