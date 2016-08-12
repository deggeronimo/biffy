<?php namespace Biffy\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GoogleImportAll extends GoogleImportCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:all';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run all Google import tasks';

    /**
     * Create a new command instance.
     *
     * @return \Biffy\Console\Commands\GoogleImportAll
     */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        if (env('fake_user', false)) {
            $this->info('Faking user, skipping Google import.');
            return;
        }

		$this->call('import:groups');
        $this->call('import:users', ['admins' => 'b.burr,c.florin,d.reiff,n.menke']);
        $this->call('import:members');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
		];
	}

}
