<?php namespace Biffy\Http\Controllers\Helpers;

use Biffy\Http\Requests\AbstractFormRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;

trait ServiceStoreControllerHelper
{
    /**
     * @param AbstractFormRequest $request
     * @return mixed
     * @throws Exception
     */
    public function store(AbstractFormRequest $request)
    {
        $input = $request->all();

        try
        {
            //TODO: Hook before storing this value
            $input = $this->beforeStore($input);

            if (is_null($input))
            {
                //TODO: Don't return a 201
                return $this->data([])->statusCreated()->respond();
            }
            else
            {
                $result = $this->service->create($input);

                $result = $this->afterStore($result);

                return $this->data($result->toArray())->statusCreated()->respond();
            }
        }
        catch (Exception $e)
        {
            $this->storeError();

            throw $e;
        }
    }

    protected function beforeStore(array $input)
    {
        return $input;
    }

    /**
     * @param Model $result
     * @return Model
     */
    protected function afterStore(Model $result)
    {
        return $result;
    }

    protected function storeError()
    {
    }
}