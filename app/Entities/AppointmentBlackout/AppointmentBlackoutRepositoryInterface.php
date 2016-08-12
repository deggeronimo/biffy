<?php namespace Biffy\Entities\AppointmentBlackout;

use Biffy\Entities\AbstractRepositoryInterface;

interface AppointmentBlackoutRepositoryInterface extends AbstractRepositoryInterface
{
    public function getAppointmentBlackoutsForStore($storeId);
}
