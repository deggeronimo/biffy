<?php namespace Biffy\Commands\TimeClock;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Entities\TimeClock\TimeClockRepositoryInterface;
use Biffy\Services\Entities\CommandFailedException;
use Biffy\Services\Entities\RollbackFailedException;

/**
 * Class OnBreakCommandHandler
 * @package Biffy\Commands\TimeClock
 */
class OnBreakCommandHandler implements CommandHandler
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
        $command->saved_id = $this->timeClockRepository->breakStart($command->store_id, $command->user_id)->id;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command)
    {
        if (isset($command->saved_id)) {
            $this->timeClockRepository->delete($command->saved_id);
        } else {
            throw new RollbackFailedException;
        }
    }
}