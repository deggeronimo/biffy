<?php namespace Biffy\Http\Controllers\Helpers;

use Biffy\Http\Requests\AbstractFormRequest;
//use Illuminate\Support\Facades\Input;
//use Watson\Validating\ValidationException;
//use Illuminate\Http\Response as IlluminateResponse;

trait UpdateControllerHelper {

    public function update()
    {
        $args = func_get_args(); //we need the last param id on the nested routes.

        $request = \App::make('Biffy\Http\Requests\AbstractFormRequest');

        // On all (plain/nested) routes, We need the last id on nested chain to perform update.
        // mysql ids are sufficient to reach any level of child, so we just grab the last argument.
        //eg: companies/5/contacts/63, here contact 63 will always reach same child regardless of parent company 5.
        $this->repo->update(array_pop($args), $request->all());

        return $this->messages('update', 'Updated successfully!')->statusUpdated ()->respond();
    }
}