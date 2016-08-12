<?php namespace Biffy\Console\Commands;

use Biffy\Services\Entities\User\UserService;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GoogleImportUsers extends GoogleImportCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:users';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import users from Google';

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * Create a new command instance.
     *
     * @return \Biffy\Console\Commands\GoogleImportUsers
     */
	public function __construct()
	{
		parent::__construct();

        $this->userService = app('Biffy\Services\Entities\User\UserService');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $admins = explode(',', $this->argument('admins'));

        $userData = $this->directory->listUsers('ubreakifix.com');

        $count = 0;
        foreach ($userData as $user) {
            $this->userService->createFromGoogle($user, $admins);
            $count++;
        }

        $this->info("Imported {$count} users.");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
            ['admins', InputArgument::OPTIONAL, 'Enter usernames for admins, separated by commas.', '']
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
