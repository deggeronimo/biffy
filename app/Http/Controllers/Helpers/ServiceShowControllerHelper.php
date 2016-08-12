<?php namespace Biffy\Http\Controllers\Helpers;

trait ServiceShowControllerHelper
{
    public function show($id)
    {
        $result = $this->service->find($id);

        return $this->data($result->toArray())->respond();
    }
}