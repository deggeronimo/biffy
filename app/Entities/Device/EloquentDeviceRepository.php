<?php namespace Biffy\Entities\Device;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentDeviceRepository
 * @package Biffy\Entities\Device
 */
class EloquentDeviceRepository extends EloquentAbstractRepository implements DeviceRepositoryInterface
{
    protected $filters = [
        'customer_id' => [ 'customer_id = ?', ':value' ]
    ];

    /**
     * @param Device $model
     */
    public function __construct(Device $model)
    {
        $this->model = $model;

        $this->with([  ]);

/*        $this->with([ 'workOrders', 'workOrders.saleItems', 'workOrders.saleItems.inventory.storeItem.item',
            'deviceType.deviceChecklist.checklistFunction',
            'deviceType.deviceChecklist.item.deviceType',
            'deviceType.deviceType.deviceChecklist.item.deviceType'
        ]);
*/
    }
}