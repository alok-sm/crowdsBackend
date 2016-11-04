<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('answers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('task_id')->unsigned()->references('id')->on('task');
			$table->integer('user_id')->unsigned()->references('id')->on('user');
			$table->string('data');
			$table->string('time_taken');
			$table->string('pre_confidence_value');
			$table->string('post_confidence_value');
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
		Schema::drop('answers');
	}

}
