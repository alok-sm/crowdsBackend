<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserDomainRankTable extends Migration {

	public function up()
	{
		Schema::table('user_domain_rank', function(Blueprint $table)
		{
			$table->string('assignment_id');
			$table->string('hit_id');
		});
	}

	public function down()
	{
		Schema::table('user_domain_rank', function(Blueprint $table) 
		{
	        $table->dropColumn('assignment_id');
	        $table->dropColumn('hit_id');
	    });
	}

}
