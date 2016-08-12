<?php namespace Biffy\Commands;

use Illuminate\Contracts\Foundation\Application;

class DefaultCommandBus implements CommandBus
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var CommandTranslator
     */
    protected $commandTranslator;

    public function __construct(Application $app, CommandTranslator $commandTranslator)
    {
        $this->app = $app;
        $this->commandTranslator = $commandTranslator;
    }

    public function execute($command)
    {
        // todo decorator

        $handler = $this->commandTranslator->toCommandHandler($command);

        return $this->app->make($handler)->handle($command);
    }

    public function rollback($command)
    {
        $handler = $this->commandTranslator->toCommandHandler($command);

        return $this->app->make($handler)->handleRollback($command);
    }
}