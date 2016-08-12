<?php namespace Biffy\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeRepositoryInterface extends GeneratorCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repo-interface';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository interface';

    protected $type = 'RepositoryInterface';

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Entities\\' . $this->argument('name');
    }

    protected function getNameInput()
    {
        return $this->argument('name') . $this->type;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Entity name for the repository interface'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/repository-interface.stub';
    }
}
