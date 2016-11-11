<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDomainRankTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_domain_rank', function(Blueprint $table)
		{
			$table->string('user_id');
			$table->integer('domain_id');
			$table->integer('usr_dom_rank');
			$table->string('assignment_id');
			$table->string('hit_id');

			$table->primary(array('user_id', 'domain_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_domain_rank');
	}

}
