<?php

namespace Biffy\Commands;

interface CommandBus
{
    public function execute($command);

    public function rollback($command);
} 