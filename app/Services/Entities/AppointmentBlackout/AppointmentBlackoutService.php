<?php namespace Biffy\Services\Entities\AppointmentBlackout;

use Biffy\Entities\AppointmentBlackout\AppointmentBlackoutRepositoryInterface;
use Biffy\Services\Entities\Service;

class AppointmentBlackoutService extends Service
{
    public function __construct(AppointmentBlackoutRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function update($id, $attributes)
    {
        $blackOut = $this->repo->find($id);

        if ($blackOut->store_id == null)
        {
            return $blackOut;
        }
        else
        {
            return $this->repo->update($id, $attributes);
        }
    }

    public function destroy($id)
    {
        $blackOut = $this->repo->find($id);

        if ($blackOut->store_id == null)
        {
            return [];
        }
        else
        {
            $this->repo->delete($id);

            return [];
        }

    }

    public function getAppointmentBlackoutsForStore($storeId)
    {
        return $this->repo->getAppointmentBlackoutsForStore($storeId)->toArray();
    }
}