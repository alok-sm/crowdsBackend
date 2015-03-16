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
		$answer1->pre_confidence_value = "2";
		$answer1->post_confidence_value = "3";
		$answer1->save();

		$answer2 = new App\Answer;
		$answer2->user_id = 1;
		$answer2->task_id = 2;
		$answer2->data = "2";
		$answer2->time_taken = "1";
		$answer2->pre_confidence_value = "2";
		$answer2->post_confidence_value = "3";
		$this->assertEquals(false, $answer2->isValid());
	}

	public function test_200_answer()
	{
		$response = $this->call('POST', '/answer', []);
		// $this->assertEquals(200, $response->getStatusCode());
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
