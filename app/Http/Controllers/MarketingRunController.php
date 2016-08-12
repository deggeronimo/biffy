<?php namespace Biffy\Http\Controllers;

use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\MarketingRun\MarketingRunService;

class MarketingRunController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;

    public function __construct(MarketingRunService $service)
    {
        $this->service = $service;
    }

    public function update($id, AbstractFormRequest $request)
    {
        $input = $request->all();

        if (isset($input['marketing_location_id']))
        {
            $marketingRun = $this->service->find($id);

            $marketingRun->marketingLocations()->attach(
                [ $input['marketing_location_id'] ],
                [
                    'marketing_run_type_id' => $input['marketing_run_type_id'],
                    'comments' => $input['comments']
                ]
            );

            return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
        }
        else
        {
            $success = $this->service->update($id, $input);

            $this->afterUpdate($id, $input);

            if ($success===true)
            {
                return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
            }
            else
            {
                return $this->messages('message', 'Not updated!')->statusOk()->respond();
            }
        }
    }
}