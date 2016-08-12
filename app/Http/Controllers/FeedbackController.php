<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Services\Entities\Feedback\FeedbackService;

class FeedbackController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    private $service;

    function __construct(FeedbackService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        //@todo Make new config setting for count per page
        $perPage = StoreConfig::get('results-per-page');
        $perPage = (is_null($perPage)?10:$perPage);

        $count = $this->input('count', $perPage);
        $page = $this->input('page', 1);

        $customerId = $this->input('customer_id');
        $filter = $customerId ? [ 'customer' => $customerId ] : $this->input('filter');

        $result = $this->service
            ->paginate($count, $page)
            ->filterBy($filter)
            ->sortBy($this->input('sorting'))->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }

    public function show($id)
    {
        $result = $this->service->find($id);

        $retVal = $this->data($result->toArray())->respond();

        return $retVal;
    }
}