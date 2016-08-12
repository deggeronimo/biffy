<?php namespace Biffy\Http\Controllers\Helpers;

use Biffy\Http\Requests\AbstractFormRequest;

trait ServiceUpdateControllerHelper
{
    public function update($id, AbstractFormRequest $request)
    {
        $input = $request->all();

        $input = $this->beforeUpdate($id, $input);

        if (is_null($input))
        {
            //TODO: Don't return a 205
            return $this->data([])->statusUpdated()->respond();
        }
        else
        {
            $success = $this->service->update($id, $input);

            if ($success === true)
            {
                $this->afterUpdate($id, $input);

                return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
            }
            else
            {
                return $this->messages('message', 'Not updated!')->statusOk()->respond();
            }
        }
    }

    /**
     * @param $id
     * @param array $result
     */
    protected function afterUpdate($id, array $result)
    {
    }

    protected function beforeUpdate($id, array $input)
    {
        return $input;
    }
}