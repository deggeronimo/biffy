<?php namespace Biffy\Http\Controllers\Helpers;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ShowControllerHelper
{
	public function show()
    {
        $args = func_get_args();

        $id = array_pop($args);

        try
        {
            $result = $this->repo->find($id);

            return $this->data($result->toArray())->respond();
        }
        catch (ModelNotFoundException $e)
        {
            return $this->statusNotFound()->respond();
        }
	}
}