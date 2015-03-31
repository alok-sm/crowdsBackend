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
		echo "HERE at start!";
		$faker = Faker\Factory::create();
		$users = Client::all()->lists('id');
		$tasks = Task::all()->lists('id');
		echo "Running loop below";
 		for( $i = 0; $i < sizeof($users); $i++ )
		{
			for( $j = 0; $j < sizeof($tasks); $j++ )
			{
				echo "Start of instance";
				$answer = new Answer;
				echo "Adding the instance";
				$answer->data = $faker -> word;
				$answer->ignore_save_condition = true;
				$answer->task_id = $tasks[$j];
				$answer->user_id = $users[$i];
				$answer->time_taken = $faker->randomNumber(2);
				$answer->confidence = $faker->randomDigit;
				echo "Done initializing";
				echo $answer->isValid();
				if ($answer->save())
				  echo "odfi";
				  else
				  echo "noooo o:( ";
			}
		}
	}
}