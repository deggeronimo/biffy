<?php namespace Biffy\Commands\TimeClock;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Entities\TimeClock\TimeClockRepositoryInterface;
use Biffy\Services\Entities\CommandFailedException;
use Biffy\Services\Entities\RollbackFailedException;

/**
 * Class VerifyClockedInCommandHandler
 * @package Biffy\Commands\TimeClock
 */
class VerifyClockedInCommandHandler implements CommandHandler
{
    private $timeClockRepository;

    public function __construct(TimeClockRepositoryInterface $timeClockRepository)
    {
        $this->timeClockRepository = $timeClockRepository;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws CommandFailedException
     */
    public function handle(AbstractCommand $command)
    {
        if (!$this->timeClockRepository->isUserClockedIn($command->store_id, $command->user_id)) {
            throw new CommandFailedException('timeclock_err_user_not_clocked_in');
        }
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command) {}
}