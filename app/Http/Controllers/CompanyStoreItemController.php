<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\CompanyStoreItem\CompanyStoreItem;
use Biffy\Facades\StoreConfig;
use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\CompanyStoreItem\CompanyStoreItemService;
use Exception;

class CompanyStoreItemController extends ApiController
{
    protected $service;

    public function __construct(CompanyStoreItemService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index($companyId)
    {
        $perPage = StoreConfig::get('results-per-page');
        $perPage = (is_null($perPage)?10:$perPage);

        $count = $this->input('count', $perPage);
        $page = $this->input('page', 1);

        $filter = $this->input('filter');
        $sorting = $this->input('sorting');

        $filter['company_id'] = $companyId;

        $result = $this->service
            ->paginate($count, $page)
            ->filterBy($filter)
            ->sortBy($sorting)
            ->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }

    public function show($companyId, $id)
    {
        $result = $this->service->find($id);

        return $this->data($result->toArray())->respond();
    }

    /**
     * @param AbstractFormRequest $request
     * @return mixed
     * @throws Exception
     */
    public function store($companyId, AbstractFormRequest $request)
    {
        $input = $request->all();

        try
        {
            //TODO: Hook before storing this value
            $input = $this->beforeStore($input);

            if (is_null($input))
            {
                //TODO: Don't return a 201
                return $this->data([])->statusCreated()->respond();
            }
            else
            {
                $input['company_id'] = $companyId;

                $result = $this->service->create($input);

                $result = $this->afterStore($result);

                return $this->data($result->toArray())->statusCreated()->respond();
            }
        }
        catch (Exception $e)
        {
            $this->storeError();

            throw $e;
        }
    }

    protected function beforeStore(array $input)
    {
        return $input;
    }

    /**
     * @param CompanyStoreItem $result
     * @return CompanyStoreItem
     */
    protected function afterStore(CompanyStoreItem $result)
    {
        return $result;
    }

    protected function storeError()
    {
    }

    public function update($companyId, $id, AbstractFormRequest $request)
    {
        $input = $request->all();

        $input = $this->beforeUpdate($id, $input);

        if (is_null($input))
        {
            //TODO: Don't return a 205
            return $this->data([])->statusUpdated()->respond();
        }
        else
        {
            $success = $this->service->update($id, $input);

            if ($success === true)
            {
                $this->afterUpdate($id, $input);

                return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
            }
            else
            {
                return $this->messages('message', 'Not updated!')->statusOk()->respond();
            }
        }
    }

    /**
     * @param $id
     * @param array $result
     */
    protected function afterUpdate($id, array $result)
    {
    }

    protected function beforeUpdate($id, array $input)
    {
        return $input;
    }

    public function destroy($companyId, $id)
    {
        try
        {
            $this->beforeDestroy($id);

            $this->service->destroy($id);

            $this->afterDestroy($id);

            return $this->statusDeleted()->respond();
        }
        catch (Exception $e)
        {
            $this->destroyError();

            throw $e;
        }
    }

    protected function beforeDestroy($id)
    {
    }

    protected function afterDestroy($id)
    {
    }

    protected function destroyError()
    {
    }
}