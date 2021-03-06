<?php namespace Biffy\Commands\Group;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Services\Directory\DirectoryService;
use Biffy\Services\Entities\CommandFailedException;
use Biffy\Services\Entities\RollbackFailedException;

class CreateGroupCommandHandler implements CommandHandler
{
    /**
     * @var DirectoryService
     */
    private $directory;

    function __construct(DirectoryService $directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws CommandFailedException
     */
    public function handle(AbstractCommand $command)
    {
        $this->directory->createGroup($command->email, $command->name);
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command)
    {

    }
}