<?php namespace Biffy\Http\Controllers\Helpers;

trait ServiceSelectControllerHelper
{
    public function select()
    {
        $args = func_get_args();

        $result = $this->service->select($args);

        $this->data($result)->respond();
    }
}
