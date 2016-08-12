<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\AppointmentBlackout\AppointmentBlackoutService;
use Illuminate\Support\Facades\Auth;

class AppointmentBlackoutController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;

    public function __construct(AppointmentBlackoutService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $filter = $this->input('filter');
        $sorting = $this->input('sorting');

        $filter['store_id'] = Auth::user()->storeId();

        $result = $this->service
            ->filterBy($filter)
            ->sortBy($sorting)->get();

        return $this->data($result->toArray())->statusOk()->respond();
    }

}