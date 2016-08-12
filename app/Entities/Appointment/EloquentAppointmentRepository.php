<?php namespace Biffy\Entities\Appointment;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentAppointmentRepository extends EloquentAbstractRepository implements AppointmentRepositoryInterface
{
    public function __construct(Appointment $model)
    {
        $this->model = $model;

        $this->with([ 'appointmentStatus', 'customer' ]);
    }
}