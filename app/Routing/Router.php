<?php namespace Biffy\Routing;

use Illuminate\Routing\Router as IlluminateRouter;

class Router extends IlluminateRouter {

    // Override to force use of Biffy\Routing\ResourceRegistrar
    public function resource($name, $controller, array $options = array())
    {
        (new ResourceRegistrar($this))->register($name, $controller, $options);
    }

}