<?php

class TaskBufferTest extends TestCase {

	/**
	 * To test the token which is being sent
	 *
	 * @return void
	 */
	public function test_empty_task_buffer()
	{
		$task_buffer_1 = new App\TaskBuffer;
		$task_buffer_1->domain_id = 2;
		$task_buffer_1->user_id = 1;
		// $answer1->time_taken = "1";
		// $answer1->pre_confidence_value = "2";
		// $answer1->post_confidence_value = "3";

		$this->assertEquals(false, $task_buffer_1->isValid());
	}

	public function test_duplication()
	{
		$task_buffer_1 = new App\TaskBuffer;
		$task_buffer_1->user_id = 1;
		$task_buffer_1->domain_id = 2;
		$task_buffer_1->task_id_list = [1,2,3,4];
		$task_buffer_1->save();

		$task_buffer_2 = new App\TaskBuffer;
		$task_buffer_2->user_id = 1;
		$task_buffer_2->domain_id = 2;
		$this->assertEquals(false, $task_buffer_2->isValid());
	}

	// public function test_200_answer()
	// {
	// 	$response = $this->call('POST', '/answer', []);
	// 	// $this->assertEquals(200, $response->getStatusCode());
	// }

	// public function test_new_domain_task()
	// {
	// 	// create_domain("Test");
	// 	// create_random_task(1);
	// }

	// private function create_domain($name)
	// {
	// 	$domain = new Domain();
	// 	$domain->name = $name;
	// 	$this->assertEquals(false, $domain->save());
	// }

	private function create_new_task($name)
	{
		// $task = new Task();

	}

}
