<?php namespace Biffy\Services\Entities\Appointment;

use Biffy\Entities\Appointment\AppointmentRepositoryInterface;
use Biffy\Services\Entities\Service;

class AppointmentService extends Service
{
    public function __construct(AppointmentRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}