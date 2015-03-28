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

		$this->assertEquals(false, $answer1->isValid());
	}

	public function test_correct_answer()
	{
		// There should be a task buffer
		$task_buffer = new App\TaskBuffer;
		$task_buffer->user_id = 3;
		$task_buffer->domain_id = 2;
		$task_buffer->task_id_list = [1, 2, 3, 4];
		$task_buffer->pre_confidence_value = 2;
		$this->assertEquals(true, $task_buffer->save());

		$answer1 = new App\Answer;
		$answer1->user_id = 3;
		$answer1->task_id = 2;
		$answer1->data = "Hello";
		$answer1->time_taken = "1";
		$answer1->confidence = 9;

		$this->assertEquals(true, $answer1->isValid());
		$this->assertEquals(true, $answer1->save());
	}

	public function test_answer_duplication()
	{
		// Create User
		$user = $this->create_user();
		// Create Domain
		$domain = $this->create_domain("Football");

		// Create Tasks of Domain
		$ids = $this->create_new_task($domain);

		$task_buffer = $this->create_task_buffer($domain, $user, 10);

		$answer1 = new App\Answer;
		$answer1->user_id = $user->id;
		$answer1->task_id = 1;
		$answer1->data = "2";
		$answer1->time_taken = "10";
		$answer1->confidence = 20;
		
		$this->assertEquals(true, $answer1->save());


		$answer2 = new App\Answer;
		$answer2->user_id = $user->id;
		$answer2->task_id = 1;
		$answer2->data = "2";
		$answer2->time_taken = "1";
		$answer1->confidence = 20;
		$this->assertEquals(false, $answer2->isValid());
		$this->assertEquals(false, $answer2->save());
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

		$cookie = cookie()->forever('crowd_id', 1);
		$response = $this->call('POST', '/answers', $arr, (array) $cookie);

		$answer = App\Answer::find(1);

		$this->assertEquals('failure', json_decode($response->getContent())->{'status'});
		$this->assertEquals(403, $response->getStatusCode());
	}

	protected function create_user()
	{
		$user = new App\Client();
		$user->age = 10;
		$user->gender = 'M';
		$user->education = 'Higher Primary';
		$this->assertEquals(true, $user->save());
		return $user;
	}

	private function create_answer($user_id, $task_id, $data, $time_taken, $confidence)
	{
		$answer = new App\Answer();
		$answer->user_id = $user_id;
		$answer->task_id = $task_id;
		$answer->data = $data;
		$answer->time_taken = $time_taken;
		$answer->confidence = $confidence;
		$this->assertEquals(true, $answer->save());
		return $answer;
	}

	private function create_domain($name)
	{
		$domain = new App\Domain();
		$domain->name = $name;
		$this->assertEquals(true, $domain->save());
		return $domain;
	}

	private function create_new_task($domain)
	{
		$ids = [];
		for ($i=0; $i < 20; $i++) { 
			$task = new App\Task();
			$task->title = 'Title '. $i;
			$task->type = 'type'. $i;
			$task->data = 'data'. $i;
			$task->answer_type = 'answer_type'. $i;
			$task->answer_data = 'answer_data'. $i;
			$task->correct_answer = 'correct_answer'. $i;
			$task->domain_id = $domain->id;
			$this->assertEquals(true, $task->save());
			array_push($ids, (string) $task->id);
		}
		return $ids;
	}

	private function create_task_buffer($domain, $user, $pre_confidence_value)
	{
		// There should be a task buffer
		$task_buffer = new App\TaskBuffer;
		$task_buffer->user_id = $user->id;
		$task_buffer->domain_id = $domain->id;
		$task_buffer->task_id_list = $domain->tasks->lists('id');
		$task_buffer->pre_confidence_value = $pre_confidence_value;
		$this->assertEquals(true, $task_buffer->save());
	}

}
