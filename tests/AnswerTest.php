<?php

class AnswerTest extends TestCase {

	/**
	 * To test the token which is being sent
	 *
	 * @return void
	 */

	public function test_empty_answer()
	{
		$answer1 = new App\Answer;
		$answer1->user_id = 1;
		$answer1->task_id = 2;
		$answer1->data = "Hello";
		// $answer1->time_taken = "1";
		// $answer1->pre_confidence_value = "2";
		// $answer1->post_confidence_value = "3";

		// $this->assertEquals(false, $answer1->isValid());
	}

	public function test_answer_duplication()
	{
		$answer1 = new App\Answer;
		$answer1->user_id = 1;
		$answer1->task_id = 2;
		$answer1->data = "2";
		$answer1->time_taken = "1";
		$answer1->save();

		$answer2 = new App\Answer;
		$answer2->user_id = 1;
		$answer2->task_id = 2;
		$answer2->data = "2";
		$answer2->time_taken = "1";
		$this->assertEquals(false, $answer2->isValid());
	}

	// This test is a useless test!
	public function test_200_answer()
	{
		$response = $this->call('GET', '/token');
		$json = json_decode($response->getContent());
		$token = $json->{'token'};

		$task_id = 1;
		$data = 'HOLA!';
		$time_taken = '10';
		$pre_confidence_value = '10';
		$pre_confidence_value = '10';

		$arr = ['_token' => $token, 'task_id' => 1, 'data' => 'HOLA!', 'time_taken' => '10'];

		// $cookie = \Cookie::queue('crowd_id', 1, 60);
		$cookie = cookie()->forever('crowd_id', 1);
		// Cookie::queue('crowd_id', 1, 60);
		// $cokie = \Cookie::getQueuedCookies();
		// echo "SENDING BELOW";
		// print_r($cookie);
		$response = $this->call('POST', '/answer', $arr, (array) $cookie);

		$answer = App\Answer::find(1);

		// $this->assertEquals($answer->task_id, $task_id);
		// $this->assertEquals($answer->data, $data);
		// $this->assertEquals($answer->time_taken, $time_taken);
		
		// $this->assertNotNull($answer->user_id);
		$this->assertEquals('failure', json_decode($response->getContent())->{'status'});
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function test_new_domain_task()
	{
		// create_domain("Test");
		// create_random_task(1);
	}

	private function create_domain($name)
	{
		$domain = new Domain();
		$domain->name = $name;
		$this->assertEquals(false, $domain->save());
	}

	private function create_new_task($name)
	{
		// $task = new Task();

	}

}
