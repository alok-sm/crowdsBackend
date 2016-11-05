<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrowserStateTable extends Migration {
	public function up()
	{
		Schema::create('browser_state', function(Blueprint $table)
		{
			$table->string('key');
			$table->string('value');
		});
	}

	public function down()
	{
		Schema::drop('browser_state');
	}

}
