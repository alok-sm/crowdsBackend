<?php

use Mockery as m;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	protected $useDatabase = true;

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$unitTesting = true;
 
    $testEnvironment = 'testing';

		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

  public function setUp()
  {
      parent::setUp();
      if($this->useDatabase)
      {
        $this->setUpDb();
      }
  }

  public function teardown()
  {
      m::close();
  }

  public function setUpDb()
  {
      Artisan::call('migrate');
      Artisan::call('db:seed');
  }

  public function teardownDb()
  {
      Artisan::call('migrate:reset');
  }

}
