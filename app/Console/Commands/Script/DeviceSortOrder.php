<?php namespace Biffy\Console\Commands\Script;

use Biffy\Services\Entities\DeviceType\DeviceTypeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeviceSortOrder extends Command
{
    protected $name = 'script:devicetype-sortorder';

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

        foreach ($topLevelDeviceList as $topLevelDevice)
        {
            if ($topLevelDevice->id == 59)
            {
                continue;
            }

            //if a DeviceType is top level, it is Level 1.  Leave sort_order in place.
            $this->checkChildren($topLevelDevice, $topLevelDevice->id);
        }
    }

    protected function checkChildren($parent, $topLevelDeviceId)
    {
        $childList = $this->deviceTypeService->clearFilters()->clearQuery()->filterBy(['parent_id' => $parent->id])->get();

        foreach ($childList as $child)
        {
//            if ($child->id == 59)
//            {
//                continue;
//            }

            if (!is_null($child->device_family_id))
//            if ($child->pos_selectable == true)
            {
                $familyId = $child->device_family_id;

                $sql = "SELECT SUM(`view_count`) as `view_count` FROM `device_types` WHERE `device_family_id` = {$familyId} GROUP BY `device_family_id`\n";

                $viewCount = DB::select(DB::raw($sql))[0]->view_count;
                $days = DB::select(DB::raw("SELECT COALESCE(DATEDIFF(`device_types`.`release_date`, '2005-01-01 00:00:00'), 0) as `days` FROM `device_types` WHERE `id` = {$child->id}"))[0]->days;

                //If a DeviceType has no children, it is Level 3.  Sort by Popular Family and Date Released
//                $countList = DB::select(DB::raw("SELECT COUNT(*) AS `view_count`, COALESCE(DATEDIFF(`device_types`.`release_date`, '2008-01-01 00:00:00'), 0) as `days`, `device_types`.`id` as `device_id` FROM  `website_views` JOIN  `device_types` ON  `device_types`.`id` =  `website_views`.`device_type_id` WHERE `device_type_id` = {$child->id}"));

//                $viewCount = 0;
//                $days = 0;
//
//                foreach ($countList as $count)
//                {
//                    $viewCount += $count->view_count;
//                    $days = $count->device_id == $child->id ? $count->days : $days;
//                }
            } else {
                $viewCount = $child->view_count;
                $days = 0;

                //If a Device Type is not root, but has children, it is Level 2.  Sort by view count over the last 30 days
//                $countList = DB::select(DB::raw("SELECT COUNT(*) AS `view_count`, 0 as `days` FROM `website_views` WHERE created_at >= UNIX_TIMESTAMP(DATE(NOW() - INTERVAL 30 DAY)) AND `website_views`.`device_type_id` = {$child->id}"));

//                $viewCount = count($countList) ? $countList[0]->view_count : 0;
//                $days = count($countList) ? $countList[0]->days : 0;
            }

            $child->sort_order = $viewCount * 100 + $days;

            $child->save();

            $this->checkChildren($child, $topLevelDeviceId);
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
