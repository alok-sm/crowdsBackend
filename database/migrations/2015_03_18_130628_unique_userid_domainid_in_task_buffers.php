<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UniqueUseridDomainidInTaskBuffers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('task_buffers', function($table)
		{
				$table->unique(array('user_id','domain_id'));
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
		echo "Dropping Unique on domain_id and user_id in task_buffers table should be done manually in DB";
	}

}
