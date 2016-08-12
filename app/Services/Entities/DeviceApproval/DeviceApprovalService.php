<?php namespace Biffy\Services\Entities\DeviceApproval;

use Biffy\Entities\DeviceApproval\DeviceApprovalRepositoryInterface;
use Biffy\Entities\DeviceCarrier\DeviceCarrierRepositoryInterface;
use Biffy\Entities\DeviceManufacturer\DeviceManufacturerRepositoryInterface;
use Biffy\Entities\DeviceType\DeviceTypeRepositoryInterface;
use Biffy\Services\Entities\Service;
use DB;

class DeviceApprovalService extends Service
{
    protected $deviceCarrierRepo;
    protected $deviceManufacturerRepo;
    protected $deviceTypeRepo;

    public function __construct(DeviceApprovalRepositoryInterface $repo, DeviceCarrierRepositoryInterface $deviceCarrierRepo,
                                DeviceManufacturerRepositoryInterface $deviceManufacturerRepo, DeviceTypeRepositoryInterface $deviceTypeRepo)
    {
        $this->repo = $repo;

        $this->deviceCarrierRepo = $deviceCarrierRepo;
        $this->deviceManufacturerRepo = $deviceManufacturerRepo;
        $this->deviceTypeRepo = $deviceTypeRepo;
    }

    public function update($id, $attributes)
    {
        if ($attributes['approved'] == '1')
        {
            DB::beginTransaction();

            $deviceApproval = $this->find($id);

            $deviceName = $deviceApproval->device_name;
            $manufacturerName = $deviceApproval->manufacturer_name;
            $carrierName = $deviceApproval->carrier_name;

            $manufacturer = $this->deviceManufacturerRepo->findFirstBy('name', $manufacturerName);

            if (is_null($manufacturer))
            {
                $manufacturer = $this->deviceManufacturerRepo->create(['name' => $manufacturerName]);
            }

            $parentDeviceType = $this->deviceTypeRepo->findFirstBy('name', $manufacturerName);

            if (!is_null($parentDeviceType))
            {
                $parentDeviceType->selectable = 0;
                $parentDeviceType->save();

                $parentDeviceTypeId = $parentDeviceType->id;
            }
            else
            {
                $parentDeviceTypeId = null;
            }

            $deviceType = $this->deviceTypeRepo->create([
                'name' => $deviceName,
                'selectable' => 1,
                'parent_device_type_id' => $parentDeviceTypeId,
                'device_manufacturer_id' => $manufacturer->id
            ]);

            $carrier = $this->deviceCarrierRepo->findFirstBy('name', $carrierName);

            if (is_null($carrier))
            {
                $carrier = $this->deviceCarrierRepo->create(['name' => $carrierName]);
            }

            $deviceType->carriers()->attach([$carrier->id]);
        }

        $result = parent::update($id, $attributes);

        if ($attributes['approved'] == '1')
        {
            DB::commit();
        }

        return $result;
    }
}