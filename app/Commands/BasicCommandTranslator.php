<?php

namespace Biffy\Commands;

class BasicCommandTranslator implements CommandTranslator
{

    public function toCommandHandler($command)
    {
        $commandClass = get_class($command);
        $handler = substr_replace($commandClass, 'CommandHandler', strrpos($commandClass, 'Command'));

        if (!class_exists($handler)) {
            throw new \Exception("Command handler [{$handler}] does not exist");
        }

        return $handler;
    }
}