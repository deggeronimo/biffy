<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\Lead\LeadRepositoryInterface;
use Biffy\Facades\StoreConfig;

/**
 * Class LeadController
 * @package Biffy\Http\Controllers
 */
class LeadController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var LeadRepositoryInterface
     */
    public $repo;

    /**
     * @param LeadRepositoryInterface $repo
     */
    function __construct(LeadRepositoryInterface $repo)
    {
        $this->repo = $repo;
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

        $result = $this->repo
            ->paginate($count, $page)
            ->filterBy($this->input('filter'))
            ->sortBy($this->input('sorting'))->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }

    /**
     *
     */
    public function export()
    {
        \Excel::create('leads', function($excel)
        {
            $excel->setTitle('List of leads');
            $excel->setCreator(config('info.export.creator'))->setCompany(config('info.export.company'));

            $excel->sheet('Leads', function($sheet)
            {
                //Export result does not limit records but maintains filter and sorting values
                $leads = $this->repo->filterBy($this->input('filter'))->sortBy($this->input('sorting'))->get()->toArray();

                $sheet->loadView('exports.LeadsList', compact('leads'));
            });
        })->download(config('info.export.extension'));
    }
}