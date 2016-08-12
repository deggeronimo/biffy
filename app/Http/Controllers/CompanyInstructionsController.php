<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\CompanyInstructions\CompanyInstructionsService;

class CompanyInstructionsController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;

    public function __construct(CompanyInstructionsService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function index($companyId)
    {
        //@todo Make new config setting for count per page
        $perPage = StoreConfig::get('results-per-page');
        $perPage = (is_null($perPage)?10:$perPage);

        $count = $this->input('count', $perPage);
        $page = $this->input('page', 1);

        $filter = $this->input('filter');
        $filter['company_id'] = $companyId;

        $result = $this->service
            ->paginate($count, $page)
            ->filterBy($filter)
            ->sortBy($this->input('sorting'))->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }

    public function show($companyId, $id)
    {
        $result = $this->service->find($id);

        return $this->data($result->toArray())->respond();
    }

    /**
     * @param $companyId
     * @param AbstractFormRequest $request
     * @return mixed
     */
    public function store($companyId, AbstractFormRequest $request)
    {
        //TODO: Hook before storing this value
//        $this->beforeStore($request);

        $input = $request->all();
        $input['company_id'] = $companyId;

        $result = $this->service->create($input);

        $this->afterStore($result);

        return $this->data([$result])->statusCreated()->respond();
    }

    public function update($companyId, $id, AbstractFormRequest $request)
    {
        $input = $request->all();
//        $input['company_id'] = $companyId;    //TODO: do this?

        $success = $this->service->update($id, $input);

        if ($success===true)
        {
            return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
        }
        else
        {
            return $this->messages('message', 'Not updated!')->statusOk()->respond();
        }
    }

    public function destroy($companyId, $id)
    {
        $this->service->destroy($id);

        return $this->statusDeleted()->respond();
    }
}