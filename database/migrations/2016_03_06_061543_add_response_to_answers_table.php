<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResponseToAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('answers', function(Blueprint $table)
		{
			//
			$table->longText('server_response');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('answers', function(Blueprint $table)
		{
			//
			$table->dropColumn('server_response');
		});
	}

}
