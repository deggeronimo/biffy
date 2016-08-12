<?php namespace Biffy\Console\Commands\Script;

use Biffy\Services\Entities\DeviceType\DeviceTypeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RootParentScript extends Command
{
    protected $name = 'script:rootparent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    public function __construct(DeviceTypeService $deviceTypeService)
    {
        parent::__construct();

        $this->deviceTypeService = $deviceTypeService;
    }

    public function fire()
    {
        $topLevelDeviceList = DB::select(DB::raw('SELECT * FROM `device_types` WHERE `top` = 1'));

        $iPhoneTopLevelDevice = null;

        foreach ($topLevelDeviceList as $topLevelDevice)
        {
            echo("Top Level Device Id: {$topLevelDevice->id}\n");

            if ($topLevelDevice->id == 59)
            {
                $iPhoneTopLevelDevice = $topLevelDevice;
                continue;
            }

            $this->checkChildren($topLevelDevice, $topLevelDevice->id);
        }

//        if (!is_null($iPhoneTopLevelDevice))
//        {
//            $this->checkChildren($iPhoneTopLevelDevice, $iPhoneTopLevelDevice->id);
//        }
    }

    protected function checkChildren($parent, $topLevelDeviceId)
    {
        $childList = $this->deviceTypeService->clearFilters()->clearQuery()->filterBy([ 'parent_id' => $parent->id ])->get();

        foreach ($childList as $child)
        {
            $child->device_type_filter_id = $topLevelDeviceId;
            $child->save();

            if ($child->pos_selectable == true)
            {
            }
            else
            {
                $this->checkChildren($child, $topLevelDeviceId);
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
