<?php namespace Biffy\Console\Commands;

use Biffy\Services\Entities\Group\GroupService;
use Biffy\Services\Entities\User\UserService;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GoogleImportGroupMembers extends GoogleImportCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:members';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import group-user associations from Google';

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var GroupService
     */
    protected $groupService;

    /**
     * Create a new command instance.
     *
     * @return \Biffy\Console\Commands\GoogleImportGroupMembers
     */
	public function __construct()
	{
		parent::__construct();

        $this->groupService = app('Biffy\Services\Entities\Group\GroupService');
        $this->userService = app('Biffy\Services\Entities\User\UserService');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $memberData = [];
        $groups = $this->groupService->all()->toArray();
        $users = $this->userService->all()->toArray();

        $users = array_combine(array_map(function ($user){
                    return $user['email'];
                }, $users), array_values($users));

        foreach ($groups as $group) {
            $groupMembers = $this->directory->listGroupMembers($group['email']);

            foreach ($groupMembers as $member) {
                if (array_key_exists('email', $member) && array_key_exists($member['email'], $users)) {
                    $memberData[$group['id']][] = $users[$member['email']]['id'];
                }
            }
        }

        foreach ($memberData as $groupId => $userIds) {
            $this->groupService->addUser($groupId, $userIds);
        }

        $this->info("Imported group members.");
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
