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

	public function test_answer_duplication()
	{
		// Create User
		$user = $this->create_user();
		// Create Domain
		$domain = $this->create_domain("Football");

		// Create Tasks of Domain
		$ids = $this->create_new_task($domain);

		$answer1 = new App\Answer;
		$answer1->user_id = 1;
		$answer1->task_id = 2;
		$answer1->data = "2";
		$answer1->time_taken = "1";
		
		if($answer1->save())
			echo "ASNWERS IS SAVED!";
		else
		{
			echo "ANSWER NOT SAVED!";
		}

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
		$response = $this->call('POST', '/answers', $arr, (array) $cookie);

		$answer = App\Answer::find(1);

		// $this->assertEquals($answer->task_id, $task_id);
		// $this->assertEquals($answer->data, $data);
		// $this->assertEquals($answer->time_taken, $time_taken);
		
		// $this->assertNotNull($answer->user_id);
		$this->assertEquals('failure', json_decode($response->getContent())->{'status'});
		$this->assertEquals(403, $response->getStatusCode());
	}

	public function test_wrong_user(){

	}

	public function test_create_task_buffer_on_new_answer()
	{
		// Create User
		$user = $this->create_user();
		// Create Domain
		$domain = $this->create_domain("Football");

		// Create Tasks of Domain
		$ids = $this->create_new_task($domain);

		$answer = $this->create_answer(1, 1, "Hello", "12");

		$answer = App\Answer::find(1);

		$task_buffer = App\TaskBuffer::find(1);

		// $array = $domain->tasks()->lists('id');

		if (($key = array_search((string) $answer->task_id, $ids)) !== false){
			unset($ids[$key]);
		}

		$this->assertNotNull($task_buffer);
		$this->assertEquals($domain->id, $task_buffer->domain_id);
		$this->assertEquals($ids, $task_buffer->task_id_list);

		$answer2 = $this->create_answer(1, 2, "Hello", "12");

		if (($key = array_search((string) $answer2->task_id, $ids)) !== false){
			unset($ids[$key]);
		}

		$task_buffer = App\TaskBuffer::find(1);
		$this->assertNotNull($task_buffer);
		$this->assertEquals($domain->id, $task_buffer->domain_id);
		$this->assertEquals($ids, $task_buffer->task_id_list);

	}

	protected function create_user()
	{
		$user = new App\Client();
		$user->age = 10;
		$user->gender = 'M';
		$user->education = 'Higher Primary';
		$user->save();
		return $user;
	}

	private function create_answer($user_id, $task_id, $data, $time_taken)
	{
		$answer = new App\Answer();
		$answer->user_id = $user_id;
		$answer->task_id = $task_id;
		$answer->data = $data;
		$answer->time_taken = $time_taken;
		$answer->save();
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
			$task->save();
			array_push($ids, (string) $task->id);
		}
		return $ids;
	}

}
