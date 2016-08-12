<?php namespace Biffy\Http\Controllers\Helpers;

trait DestroyControllerHelper {

	public function destroy()
    {
        $args = func_get_args();

        $id = array_pop($args);

        $result = $this->repo->delete($id);

        if(!$result)
        {
            return $this->statusBadRequest()->respond();
        }
        else
        {
            return $this->statusDeleted()->respond();
        }
	}

}