<?php namespace Biffy\Commands;

interface CommandHandler
{
    /**
     * @param AbstractCommand $command
     *
     * @throws \Biffy\Services\Entities\CommandFailedException
     */
    public function handle(AbstractCommand $command);

    /**
     * @param AbstractCommand $command
     *
     * @throws \Biffy\Services\Entities\RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command);
}