<?php namespace Biffy\Http\Controllers\Helpers;

use Biffy\Http\Requests\AbstractFormRequest;

trait StoreControllerHelper {

    public function store(AbstractFormRequest $request)
    {
        //TODO: Hook before storing this value
//        $this->beforeStore($request);

        //@todo [LOW] On nested routes, we do not grab parent id from url (Laravel route matcher passes them into this function), due to which the post requests from angular must contain all parent ids on nested chain.
        // This is not an issue on `index`, `update` & `destroy` but `store` only. Model attributes are exposed to ft end this issue is not huge.
        // [Not recommended] Implementing store method on each controller is one solution but reduces development time.
        $result = $this->repo->create($request->all());

        $this->afterStore($result->toArray());

        return $this->data([$result])->statusCreated()->respond();
    }
}