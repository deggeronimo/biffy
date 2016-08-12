<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\BodRecord\BodRecordService;

class BodRecordController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;

    public function __construct(BodRecordService $service)
    {
        $this->service = $service;
    }

    private function beforeStore(array $input)
    {
        $input['source_ip'] = $_SERVER['REMOTE_ADDR'];

        return $input;
    }
}