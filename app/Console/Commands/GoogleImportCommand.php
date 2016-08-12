<?php namespace Biffy\Console\Commands;

use Illuminate\Console\Command;

abstract class GoogleImportCommand extends Command
{
    /**
     * @var \Biffy\Services\Directory\DirectoryService
     */
    protected $directory;

    public function __construct()
    {
        parent::__construct();

        $this->directory = app('Biffy\Services\Directory\DirectoryService');
    }
} 