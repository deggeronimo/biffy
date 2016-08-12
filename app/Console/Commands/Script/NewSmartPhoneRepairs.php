<?php namespace Biffy\Console\Commands\Script;

use Biffy\Services\Entities\DeviceRepair\DeviceRepairService;
use Biffy\Services\Entities\DeviceType\DeviceTypeService;
use Illuminate\Console\Command;

class NewSmartPhoneRepairs extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'script:repairs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    protected $deviceTypeList = [];
    protected $bottomDeviceTypeList = [];

    protected $repairTypeList = [ 24, 40, 41, 43, 44, 45, 46 ];

    /**
     * Create a new command instance.
     *
     * @param DeviceRepairService $deviceRepairService
     * @param DeviceTypeService $deviceTypeService
     */
    public function __construct(DeviceRepairService $deviceRepairService, DeviceTypeService $deviceTypeService)
    {
        parent::__construct();

        $this->deviceRepairService = $deviceRepairService;
        $this->deviceTypeService = $deviceTypeService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $smartPhoneDeviceType = $this->deviceTypeService->find(11);

        $this->checkChildren($smartPhoneDeviceType);

        foreach ($this->bottomDeviceTypeList as $deviceType)
        {
            $repairs = $deviceType->repairs;

            foreach ($this->repairTypeList as $repairTypeId)
            {
                $i = 0;
                foreach ($repairs as $repair)
                {
                    if ($repair->device_repair_type_id == $repairTypeId)
                    {
                        break;
                    }
                    $i ++;
                }

                if ($i == $repairs->count())
                {
                    echo("Adding Repair {$repairTypeId} to {$deviceType->name}\n");
                    $this->addRepair($deviceType, $repairTypeId);
                }

                $deviceType->product = 0;
                $deviceType->save();
            }
        }
    }

    protected function addRepair($deviceType, $repairTypeId)
    {
        $attributes = [
            'device_type_id' => $deviceType->id,
            'device_repair_type_id' => $repairTypeId
        ];

        $repair = $this->deviceRepairService->create($attributes);
    }

    protected function checkChildren($parentDeviceType)
    {
        if ($parentDeviceType->pos_selectable)
        {
            $this->bottomDeviceTypeList[] = $parentDeviceType;
        }
        else
        {
            $deviceTypeList = $this->deviceTypeService->reset()->filterBy([ 'parent_id' => $parentDeviceType->id ])->get();

            foreach ($deviceTypeList as $deviceType)
            {
                if ($deviceType->pos_selectable == true)
                {
                    $this->bottomDeviceTypeList[] = $deviceType;
                }
                else
                {
                    $this->checkChildren($deviceType);
                }
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }
}
