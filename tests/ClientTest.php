<?php

class ClientTest extends TestCase {

	protected $useDatabase = true;

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testCreateClient()
	{
		// 1st request to get the token
		$response = $this->call('GET', '/token');
		$json = json_decode($response->getContent());
		$token = $json->{'token'};

		$gender = (rand(0,1) == 0)? 'M' : 'F';
		$country = chr(rand(65,90)) . chr(rand(65,90));
		$age = rand(10,100);

		$arr = array('_token' => $token, 'age' => $age, 'gender' => $gender, 'country' => $country);

		// 2nd request to create the user
		$response = $this->call('POST', '/users', $arr);

		// echo $response;

		$client = App\Client::find(1);

		// echo $client;

		// var_dump(get_class_methods($response));

		$this->assertEquals($client->age, $age);
		$this->assertEquals($client->gender, $gender);
		$this->assertEquals($client->country, $country);
		$this->assertEquals(200, $response->getStatusCode());
	}

}
