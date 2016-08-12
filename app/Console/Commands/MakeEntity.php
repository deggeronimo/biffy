<?php namespace Biffy\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeEntity extends GeneratorCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'make:entity';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create an entity and accompanying classes';

    protected $type = 'Entity';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $name = $this->getNameInput();
		// create entity
        parent::fire();

        // create repository interface
        $this->call('make:repo-interface', ['name' => $name]);

        // create eloquent repository
        $this->call('make:repo-eloquent', ['name' => $name]);
	}

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Entities\\' . $this->getNameInput();
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'Name for the entity'],
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
        return __DIR__ . '/stubs/entity.stub';
    }
}
