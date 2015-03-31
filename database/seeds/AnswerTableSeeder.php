<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\TaskBuffer;
use App\Client;
use App\Domain;
use App\Task;
use App\Answer;
class AnswerTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Faker\Factory::create();
		$users = Client::all()->lists('id');
		$tasks = Task::all()->lists('id');
 		for( $i = 0; $i < sizeof($users); $i++ )
		{
			for( $j = 0; $j < sizeof($tasks); $j++ )
			{
				$answer = new Answer;
				$answer->data = $faker ->randomNumber(2);
				$answer->ignore_save_condition = true;
				$answer->task_id = $tasks[$j];
				$answer->user_id = $users[$i];
				$answer->time_taken = $faker->randomNumber(2);
				$answer->confidence = $faker->randomDigit;
				$answer->save();
			}
		}
	}
}