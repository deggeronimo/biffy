<?php

namespace Biffy\Commands;

interface CommandTranslator
{
    public function toCommandHandler($command);
} 