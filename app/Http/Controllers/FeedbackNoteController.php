<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\FeedbackNote\FeedbackNoteService;
use Illuminate\Support\Facades\Auth;

class FeedbackNoteController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(FeedbackNoteService $service)
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

    public function store(AbstractFormRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::user()->userId();

        $result = $this->service->create($input);

        return $this->data([$result])->statusCreated()->respond();
    }
}