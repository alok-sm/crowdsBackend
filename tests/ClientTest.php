<?php

class ClientTest extends TestCase {

	protected $useDatabase = true;

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testCreateofClient()
	{
		$gender = (rand(0,1) == 0)? 'M' : 'F';
		$country = chr(rand(65,90)) . chr(rand(65,90));
		$age = rand(10,100);
		$education = "Middle School";
		$employment = "Software Engineer";
		$arr = array('age' => $age, 'gender' => $gender, 'education' => $education, 'employment' => $employment);

		// 2nd request to create the user
		$response = $this->call('POST', '/users', $arr);

		// echo $response;

		$client = App\Client::find(1);

		// echo $client;

		// var_dump(get_class_methods($response));
		$this->assertEquals($client->age, $age);
		$this->assertEquals($client->gender, $gender);
		$this->assertEquals($client->employment, $employment);
		$this->assertEquals($client->education, $education);
		$this->assertNotNull($client->experimental_condition);
		$this->assertNotNull(json_decode($response->getContent())->{'token'});
		$this->assertEquals('success', json_decode($response->getContent())->{'status'});
		$this->assertEquals(200, $response->getStatusCode());
	}
	
	public function testTaskonCreateClient()
	{
		// 1st request to get the token
		$response = $this->call('GET', '/token');
		$json = json_decode($response->getContent());
		$token = $json->{'token'};

		$gender = (rand(0,1) == 0)? 'M' : 'F';
		$country = chr(rand(65,90)) . chr(rand(65,90));
		$age = rand(10,100);
		$education = "Middle School";
		$employment = "Software Engineer";

		$arr = array('_token' => $token, 'age' => $age, 'gender' => $gender, 'country' => $country, 'education' => $education, 'employment' => $employment);

		// 2nd request to create the user
		$response = $this->call('POST', '/users', $arr);
		$json = json_decode($response->getContent());

		// $this->assertNotNull($json->{'task'});
		$this->assertEquals('success', $json->{'status'});
		$this->assertEquals(200, $response->getStatusCode());
	}


}
