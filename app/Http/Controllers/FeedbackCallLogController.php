<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Services\Entities\FeedbackCallLog\FeedbackCallLogService;

class FeedbackCallLogController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    private $service;

    function __construct(FeedbackCallLogService $service)
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

        $result = $this->service
            ->paginate($count, $page)
            ->filterBy($this->input('filter'))
            ->sortBy($this->input('sorting'))->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }
}