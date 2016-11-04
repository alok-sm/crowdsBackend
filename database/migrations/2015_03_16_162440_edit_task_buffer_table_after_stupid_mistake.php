<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTaskBufferTableAfterStupidMistake extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('task_buffer', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('domain_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('domain_id')->references('id')->on('domains');			
			$table->text('task_id_list');
			
			$table->timestamps();	
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
		Schema::drop('task_buffer');
	}

}
