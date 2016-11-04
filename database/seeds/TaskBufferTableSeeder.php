<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\TaskBuffer;
use App\Client;
use App\Domain;
use App\Task;
use App\Answer;
class TaskBufferTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Faker\Factory::create();
		$users = Client::all() -> lists('id');
		$domains = Domain::all() -> lists('id');
 		for($i = 0; $i < sizeof($users); $i++ )
		{
			for( $j = 0; $j < sizeof($domains); $j++ )
			{
				//$tasks = Task::where('domain_id', $domains[$j]) -> lists('id');
				$task_buffer = new TaskBuffer;
				$task_buffer->user_id = $users[$i];
				$task_buffer->domain_id = $domains[$j];
				$task_buffer->task_id_list = [];
				$task_buffer->pre_confidence_value = $faker->randomNumber(2);
				$task_buffer->post_confidence_value = $faker->randomNumber(2);
				$task_buffer->save();	
				/*
				$t = TaskBuffer::create(array(
				'user_id' => $users[$i],
				'domain_id' => $domains[$j],
				'task_id_list' => [],
				'pre_confidence_value' => $faker -> randomNumber(2),
				'post_confidence_value' => $faker -> randomNumber(2)
				));
				if ($t)
					echo "YAY! ";
					else
					echo "NAH! ";
					*/
			}
		}
	}

}
