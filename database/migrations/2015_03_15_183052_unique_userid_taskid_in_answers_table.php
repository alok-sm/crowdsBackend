<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UniqueUseridTaskidInAnswersTable extends Migration {

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
				$table->unique(array('task_id','user_id'));
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
				$table->dropUnique(array('task_id','user_id'));
		});
	}

}
