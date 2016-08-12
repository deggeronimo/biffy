<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\ReceiveItem\ReceiveItem;
use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\ReceiveItem\ReceiveItemService;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class ReceiveItemController
 * @package Biffy\Http\Controllers
 */
class ReceiveItemController extends ApiController
{
    /**
     * @var ReceiveItemService
     */
    protected $service;

    /**
     * @param ReceiveItemService $service
     */
    public function __construct(ReceiveItemService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->data($this->service->all()->toArray())->respond();
    }

    /**
     * @param $purchaseItemId
     * @param AbstractFormRequest $request
     * @return mixed
     * @throws Exception
     */
    public function store($purchaseItemId, AbstractFormRequest $request)
    {
        $input = $request->all();

        try
        {
            //TODO: Hook before storing this value
            $input = $this->beforeStore($input);

            if (is_null($input))
            {
                dd('null input');

                //TODO: Don't return a 201
                return $this->data([])->statusCreated()->respond();
            }
            else
            {
                $result = $this->service->receivePurchaseItem($purchaseItemId, $input['quantity']);

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
        DB::beginTransaction();

        return $input;
    }

    /**
     * @param ReceiveItem $result
     * @return Model
     */
    protected function afterStore(ReceiveItem $result)
    {
        DB::commit();

        return $result;
    }

    protected function storeError()
    {
        DB::rollback();
    }

    public function destroy($purchaseItemId, $id)
    {
        try
        {
            $this->beforeDestroy($purchaseItemId, $id);

            $this->service->unreceivePurchaseItem($purchaseItemId, $id);

            $this->afterDestroy($purchaseItemId, $id);

            return $this->statusDeleted()->respond();
        }
        catch (Exception $e)
        {
            $this->destroyError();

            throw $e;
        }
    }

    protected function beforeDestroy($purchaseItemId, $id)
    {
        DB::beginTransaction();
    }

    protected function afterDestroy($purchaseItemId, $id)
    {
        DB::commit();
    }

    protected function destroyError()
    {
        DB::rollback();
    }

}