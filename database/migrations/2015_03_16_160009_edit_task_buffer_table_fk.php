<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTaskBufferTableFk extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('task_buffer', function($table)
		{
				$table->foreign('domain_id')->references('id')->on('domains');
				$table->foreign('user_id')->references('id')->on('users');
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
			$table->dropForeign('domain_id');
			$table->dropForeign('user_id');
	}

}
