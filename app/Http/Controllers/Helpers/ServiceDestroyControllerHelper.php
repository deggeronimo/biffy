<?php namespace Biffy\Http\Controllers\Helpers;

use Exception;

trait ServiceDestroyControllerHelper
{
    public function destroy($id)
    {
        try
        {
            $this->beforeDestroy($id);

            $this->service->destroy($id);

            $this->afterDestroy($id);

            return $this->statusDeleted()->respond();
        }
        catch (Exception $e)
        {
            $this->destroyError();

            throw $e;
        }
    }

    protected function beforeDestroy($id)
    {
    }

    protected function afterDestroy($id)
    {
    }

    protected function destroyError()
    {
    }
}