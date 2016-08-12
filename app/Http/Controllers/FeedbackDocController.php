<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Http\Controllers\Helpers\RandomStringGenerator;
use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\FeedbackDoc\FeedbackDocService;
use Illuminate\Support\Facades\Input;

class FeedbackDocController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;
    use RandomStringGenerator;

    private $service;

    function __construct(FeedbackDocService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if (!is_null($this->input('unassigned')))
        {
            $result = $this->service->getUnassignedFeedbackDocs();

            return $this->data($result)->statusOk()->respond();
        }
        else
        {
            $perPage = StoreConfig::get('results-per-page');
            $perPage = (is_null($perPage)?10:$perPage);

            $count = $this->input('count', $perPage);
            $page = $this->input('page', 1);

            $result = $this->service->paginate($count, $page)->filterBy($this->input('filter'))
                ->sortBy($this->input('sorting'))->get();

            return $this->data($result->toArray()['data'])->paginator($result)->respond();
        }
    }

    public function store(AbstractFormRequest $request)
    {
        if (Input::file('file')->isValid())
        {
            $file = Input::file('file');

            $input = $request->all();
            $input['filename'] = $this->randomString() . '.' . $this->getExtension($file->getClientOriginalName());

            $destinationPath = public_path() . '/uploads/';

            if (Input::file('file')->move($destinationPath, $input['filename']))
            {
                $result = $this->service->create($input);

                return $this->data($result)->statusCreated()->respond();
            }
        }
    }

    private function getExtension($filename)
    {
        $arr = explode('.', $filename);

        return $arr[count($arr)-1];
    }
}