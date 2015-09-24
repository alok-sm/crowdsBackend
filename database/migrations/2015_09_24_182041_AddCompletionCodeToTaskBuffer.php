<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompletionCodeToTaskBuffer extends Migration {

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
      $table->string('completion_code');
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
      $table->dropColumn('completion_code');
		});
	}

}
