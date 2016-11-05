<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocialCountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('social_counts', function(Blueprint $table)
		{
			$table->integer('social_id');
			$table->integer('count');
		});

		$social_counts = DB::table('social_counts');
		$social_counts->insert(['social_id' => 0, 'count' => 0]);
		$social_counts->insert(['social_id' => 1, 'count' => 0]);
		$social_counts->insert(['social_id' => 2, 'count' => 0]);
		$social_counts->insert(['social_id' => 4, 'count' => 0]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('social_counts');
	}

}
