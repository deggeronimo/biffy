<?php namespace Biffy\Console\Commands;

use Biffy\Services\Websocket\Client;
use Illuminate\Console\Command;
use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

class StartWebsocket extends Command {
	protected $name = 'websocket:start';

	protected $description = 'Start websocket';

	public function fire()
	{
		$router = new Router();

        $transportProvider = new RatchetTransportProvider(env('WS_ADDR', '127.0.0.1'), env('WS_PORT', 8080));
        $client = new Client('realm1');

        $router->addTransportProvider($transportProvider);
        $router->addInternalClient($client);

        $router->start();
	}
}
