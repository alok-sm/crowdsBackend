<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainCounts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('domain_counts', function(Blueprint $table)
		{
			$table->integer('domain_id');
			$table->integer('count');
		});

		$domains = DB::table('domains')->select('id')->get();

		$domain_counts = DB::table('domain_counts');

		foreach ($domains as $d)
		{
			$domain_counts->insert(['domain_id' => $d->id, 'count' => 0]);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('domain_counts');
	}

}
