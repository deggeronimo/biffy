<?php namespace Biffy\Console\Commands;

use Illuminate\Console\Command;

class TestSetupCommand extends Command
{
	protected $name = 'db:test-setup';

	protected $description = 'Set up testing database';

	public function fire()
	{
		$file = storage_path('testing-setup.sqlite');

        if (file_exists($file)) {
            unlink($file);
        }

        touch($file);

        $this->call('migrate', ['--database' => 'testing-setup']);
        $this->call('db:seed', ['--class' => 'TestingSeeder', '--database' => 'testing-setup']);
	}

	protected function getArguments()
	{
		return [];
	}

	protected function getOptions()
	{
		return [];
	}

}
