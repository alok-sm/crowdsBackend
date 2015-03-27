<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditColumnsUserstable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('users', function($table)
		{
			 $table->dropColumn('country');
			 $table->string('education');
			 $table->string('employment');
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
		Schema::table('users', function($table)
		{
			 $table->string('country');
			 $table->dropColumn('education');
			 $table->dropColumn('employment');
	});
	}

}
