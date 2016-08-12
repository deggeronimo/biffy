<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Services\Entities\Appointment\AppointmentService;

class AppointmentController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        //@todo Make new config setting for count per page
        $perPage = StoreConfig::get('results-per-page');
        $perPage = (is_null($perPage)?10:$perPage);

        $count = $this->input('count', $perPage);
        $page = $this->input('page', 1);

        $result = $this->service
            ->paginate($count, $page)
            ->filterBy($this->input('filter'))
            ->sortBy($this->input('sorting'))->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }

    public function show($id)
    {
        $result = $this->service->find($id);

        $result->time = strtotime($result->time);

        return $this->data($result->toArray())->respond();
    }

    protected function beforeStore(array $input)
    {
        if (!isset($input['time']))
        {
            return null;
        }

        $input['time'] = date('Y-m-d H:i:s', $input['time']);

        return $input;
    }
}