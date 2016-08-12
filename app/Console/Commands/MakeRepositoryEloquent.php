<?php namespace Biffy\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeRepositoryEloquent extends GeneratorCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repo-eloquent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository interface';

    protected $type = 'Eloquent|Repository';

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replaceInterface($stub)->replaceEntity($stub);
    }

    protected function replaceInterface(&$stub)
    {
        $stub = str_replace('{{interface}}', $this->argument('name') . 'RepositoryInterface', $stub);
        return $this;
    }

    protected function replaceEntity($stub)
    {
        $stub = str_replace('{{entity}}', $this->argument('name'), $stub);
        return $stub;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Entities\\' . $this->argument('name');
    }

    protected function getNameInput()
    {
        $type = explode('|', $this->type);
        return $type[0] . $this->argument('name') . $type[1];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Entity name for the Eloquent repository'],
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
        return __DIR__ . '/stubs/repository-eloquent.stub';
    }
}
