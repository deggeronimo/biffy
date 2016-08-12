<?php namespace Biffy\Http\Controllers;

use Biffy\Facades\StoreConfig;
use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\AccountExpense\AccountExpenseService;
use Illuminate\Support\Facades\Input;

class AccountExpenseController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;

    public function __construct(AccountExpenseService $service)
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

    public function store(AbstractFormRequest $request)
    {
        $input = $request->all();

        $file = Input::file('file');

        if (!is_null($file) && $file->isValid())
        {
            $file = Input::file('file');

            $input['filename'] = $this->randomfileName() . '.' . $this->getExtension($file->getClientOriginalName());

            $destinationPath = public_path() . '/uploads/';

            if ($file->move($destinationPath, $input['filename']))
            {
                $result = $this->service->create($input);

                return $this->data($result->toArray())->statusCreated()->respond();
            }
            else
            {
                return $this->statusInternalError()->respond();
            }
        }
        else
        {
            $input['filename'] = '';
            $result = $this->service->create($input);

            return $this->data($result->toArray())->statusCreated()->respond();
        }
    }

    private function randomFileName()
    {
        static $chars = '0123456789abcdef';

        $retVal = '';
        for ($i = 0; $i < 32; $i ++)
        {
            $retVal = $retVal . $chars[mt_rand(0, 15)];
        }

        return $retVal;
    }

    private function getExtension($filename)
    {
        $arr = explode('.', $filename);

        return $arr[count($arr)-1];
    }
}