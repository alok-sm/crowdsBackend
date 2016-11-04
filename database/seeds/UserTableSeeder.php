<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Client;
class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Faker\Factory::create();
		
 		foreach( range(1, 200) as $item )
		{
			Client::create(array(
			'age' => $faker->randomNumber(2),
			'gender' => $faker->randomElement($array = array ('M','F')),
			'education' => $faker->word,
			'employment' => $faker->word,
			));
		}
	}

}
