<?php namespace Biffy\Entities\AppointmentBlackout;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentAppointmentBlackoutRepository extends EloquentAbstractRepository implements AppointmentBlackoutRepositoryInterface
{
    /**
     * @var array
     */
    protected $filters = [
        'store_id' => ['store_id  = ? OR store_id IS null', ':value']
    ];

    public function __construct(AppointmentBlackout $model)
    {
        $this->model = $model;
    }

    public function getAppointmentBlackoutsForStore($storeId)
    {
        $query = $this->make();

        $result = $query->whereRaw("store_id = $storeId OR store_id IS null")->get();

        return $result;
    }
}