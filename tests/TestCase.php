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
      $this->resetEvents();
  }

  private function resetEvents()
  {
      // Define the models that have event listeners.
      $models = array('App\Client', 'App\Task', 'App\Domain', 'App\User');

      // Reset their event listeners.
      foreach ($models as $model) {

          // Flush any existing listeners.
          call_user_func(array($model, 'flushEventListeners'));

          // Reregister them.
          call_user_func(array($model, 'boot'));
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
