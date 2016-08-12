<?php namespace Biffy\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeJs extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'make:js';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates state, controller, and template files for a section';

    /**
     * @var Filesystem
     */
    protected $files;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->files = $filesystem;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $root = $this->option('root');
        $state = $this->argument('state');
        $data = ['state' => $state, 'parent' => ''];

        if ($root) {
            $name = $this->argument('name');
            if (is_null($name)) {
                $this->error('Name argument required when --root is used');
                return;
            }

            $data['parent'] = "\nparent: 'authorized',";
            $state .= '.' . $name;
        } else {

        }
        $stateParts = explode('.', $state);

        $data['controller'] = $this->buildControllerName($stateParts);
        $data['template'] = $this->buildTemplateUrl($stateParts, $root);

        $path = $this->getPath($stateParts, $root);
        $this->makeDirectory($path . 'tmp');

        foreach ($this->getStubs() as $k => $stub) {
            $this->files->put($path . str_replace('{{name}}', end($stateParts), $k), $this->buildFile($stub, $data));
        }
	}

    protected function getPath($stateParts, $root)
    {
        if ($root) {
            $path = reset($stateParts);
        } else {
            $path = implode('/', $stateParts);
        }
        return $this->laravel['path'] . '/../theme/src/' . $path . '/';
    }

    protected function buildTemplateUrl($stateParts, $root)
    {
        $url = 'src/';
        $url .= implode('/', $stateParts);

        if ($root) {
            $url .= '.html';
        } else {
            $url .= '/' . end($stateParts) . '.html';
        }

        return $url;
    }

    protected function buildControllerName($stateParts)
    {
        $stateParts = array_map(function ($val) {
                return ucfirst($val);
            }, $stateParts);
        return implode('', $stateParts) . 'Controller';
    }

    protected function buildFile($stub, $data)
    {
        $stub = $this->files->get($stub);

        foreach ($data as $k => $d) {
            $stub = str_replace('{{' . $k . '}}', $d, $stub);
        }

        return $stub;
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['state', InputArgument::REQUIRED, 'Name of state'],
            ['name', InputArgument::OPTIONAL, 'If using --root, specify for filename ie. list']
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
			['root', null, InputOption::VALUE_NONE, 'Flag to place in src, include name argument', null],
		];
	}

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    protected function getStubs()
    {
        $stubs = [
            '{{name}}.controller.js' => __DIR__ . '/stubs/js-controller.stub',
            '{{name}}.js' => __DIR__ . '/stubs/js-state.stub',
            '{{name}}.html' => __DIR__ . '/stubs/js-template.stub'
        ];
        return $stubs;
    }

}
