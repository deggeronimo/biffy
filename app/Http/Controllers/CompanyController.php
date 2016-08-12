<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Services\Entities\Company\CompanyService;
use Illuminate\Support\Facades\Auth;

/**
 * Class CompanyController
 * @package Biffy\Http\Controllers
 */
class CompanyController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @param CompanyService $service
     */
    function __construct(CompanyService $service)
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

        $filter = $this->input('filter');
        $filter = is_null($filter) ? [ 'store_id' => Auth::user()->storeId() ] : array_merge($filter, [ 'store_id' => Auth::user()->storeId() ]);

        $result = $this->service
            ->paginate($count, $page)
            ->filterBy($filter)
            ->sortBy($this->input('sorting'))->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }

    public function export()
    {
        \Excel::create('companies', function($excel)
        {
            $excel->setTitle('List of companies');
            $excel->setCreator(config('info.export.creator'))->setCompany(config('info.export.company'));

            $excel->sheet('Companies', function($sheet)
            {
                //Export result does not limit records but maintains filter and sorting values
                $companies = $this->service->with(['contacts'])->filterBy($this->input('filter'))->sortBy($this->input('sorting'))->get()->toArray();
                $sheet->loadView('exports.CompaniesList', compact('companies'));
            });
        })->download(config('info.export.extension'));
    }
}