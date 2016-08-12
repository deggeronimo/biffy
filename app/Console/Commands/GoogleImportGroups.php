<?php namespace Biffy\Console\Commands;

use Biffy\Services\Entities\Group\GroupService;
use Biffy\Services\Entities\Store\StoreService;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GoogleImportGroups extends GoogleImportCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:groups';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import groups from Google';

    /**
     * @var GroupService
     */
    protected $groupService;

    /**
     * @var StoreService
     */
    protected $storeService;

    /**
     * Create a new command instance.
     *
     * @return \Biffy\Console\Commands\GoogleImportGroups
     */
	public function __construct()
	{
		parent::__construct();

        $this->groupService = app('Biffy\Services\Entities\Group\GroupService');
        $this->storeService = app('Biffy\Services\Entities\Store\StoreService');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$storeOnly = $this->option('store-only');

        $groupData = $this->directory->listGroups('ubreakifix.com');

        $count = 0;
        $storeCount = 0;
        foreach ($groupData as $group) {
            $isStoreGroup = $this->groupService->isStoreGroup($group->email);
            if ($storeOnly && !$isStoreGroup) {
                continue;
            }

            $groupId = $this->groupService->createFromGoogle($group);

            if ($isStoreGroup) {
                $this->storeService->createFromGoogle($group, $groupId);
                $storeCount++;
            }

            $count++;
        }

        $this->info("Imported {$count} groups. Created {$storeCount} stores.");
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
			['store-only', null, InputOption::VALUE_NONE, 'Only import store groups', null],
		];
	}

}
