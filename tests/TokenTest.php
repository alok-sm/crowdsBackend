<?php

class TokenTest extends TestCase {

	/**
	 * To test the token which is being sent
	 *
	 * @return void
	 */
	public function testchecktoken()
	{
		$response = $this->call('GET', '/token');
		$json = json_decode($response->getContent());
		$token = $json->{'token'};

		$this->assertEquals(200, $response->getStatusCode());
		$this->assertNotEmpty($token);
	}

}
