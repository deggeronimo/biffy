<?php namespace Biffy\Http\Controllers;

use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\WorkOrderNote\WorkOrderNoteService;

/**
 * Class WorkOrderNoteController
 * @package Biffy\Http\Controllers
 */
class WorkOrderNoteController extends ApiController
{
    use Helpers\ServiceListControllerHelper;

    /**
     * @var WorkOrderNoteService
     */
    protected $service;

    /**
     * @param WorkOrderNoteService $service
     */
    function __construct(WorkOrderNoteService $service)
    {
        $this->service = $service;
    }

    public function show($workOrderId, $id)
    {
        $result = $this->service->find($id);

        return $this->data($result->toArray())->respond();
    }

    /**
     * @param $workOrderId
     * @param AbstractFormRequest $request
     * @return mixed
     */
    public function store($workOrderId, AbstractFormRequest $request)
    {
        $input = $request->all();

        $input['diag'] = json_encode($input['diag']);
        $input['user_id'] = \Auth::user()->userId();
        $input['auto'] = false;
        $result = $this->service->insertWorkOrderNote($workOrderId, $input);

        return $this->data($result)->statusCreated()->respond();
    }
}