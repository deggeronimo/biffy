<?php namespace Biffy\Commands\TimeClock;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Entities\TimeClock\TimeClockRepositoryInterface;
use Biffy\Services\Entities\CommandFailedException;
use Biffy\Services\Entities\RollbackFailedException;

/**
 * Class ClockOutCommandHandler
 * @package Biffy\Commands\TimeClock
 */
class ClockOutCommandHandler implements CommandHandler
{
    /**
     * @var TimeClockRepositoryInterface
     */
    private $timeClockRepository;

    /**
     * @param TimeClockRepositoryInterface $timeClockRepository
     */
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
        $command->timeClockItem = $this->timeClockRepository->clockOut($command->store_id, $command->user_id);
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command)
    {
        if (isset($command->timeClockItem)) {
            $command->timeClockItem->time_out = null;
            $command->timeClockItem->save();
        } else {
            throw new RollbackFailedException;
        }
    }
}