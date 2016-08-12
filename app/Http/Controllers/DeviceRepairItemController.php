<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\DeviceRepairOptionItem\DeviceRepairOptionItemService;

class DeviceRepairItemController extends ApiController
{
    public function __construct(DeviceRepairOptionItemService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $deviceRepairId
     * @return mixed
     */

    public function index($deviceRepairId)
    {
        //@todo Make new config setting for count per page
        $perPage = StoreConfig::get('results-per-page');
        $perPage = (is_null($perPage)?10:$perPage);

        $count = $this->input('count', $perPage);
        $page = $this->input('page', 1);

        $filter = $this->input('filter');
        $filter = is_null($filter) ? [ 'device_repair_id' => $deviceRepairId ] : array_merge($filter, [ 'device_repair_id' => $deviceRepairId ]);

        $result = $this->service
            ->paginate($count, $page)
            ->filterBy($filter)
            ->sortBy($this->input('sorting'))->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }

    public function show($deviceRepairId, $id)
    {
        $result = $this->service->find($id);

        return $this->data($result->toArray())->respond();
    }

    /**
     * @param $deviceRepairId
     * @param AbstractFormRequest $request
     * @return mixed
     */
    public function store($deviceRepairId, AbstractFormRequest $request)
    {
        //TODO: Hook before storing this value
//        $this->beforeStore($request);

        $input = $request->all();
        $input['device_repair_id'] = $deviceRepairId;

        $result = $this->service->create($input);

        $this->afterStore($result);

        return $this->data([$result])->statusCreated()->respond();
    }

    public function update($deviceRepairId, $id, AbstractFormRequest $request)
    {
        $input = $request->all();

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

    public function destroy($deviceRepairId, $id)
    {
        $this->service->destroy($id);

        return $this->statusDeleted()->respond();
    }
}