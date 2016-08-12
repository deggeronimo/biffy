<?php namespace Biffy\Console\Commands\Script;

use Biffy\Services\Entities\DeviceRepair\DeviceRepairService;
use Biffy\Services\Entities\DeviceType\DeviceTypeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FillUrlAliasTableScript extends Command
{
    protected $name = 'script:urlalias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    protected $deviceRepairService;
    protected $deviceTypeService;

    public function __construct(DeviceRepairService $deviceRepairService, DeviceTypeService $deviceTypeService)
    {
        parent::__construct();

        $this->deviceRepairService = $deviceRepairService;
        $this->deviceTypeService = $deviceTypeService;
    }

    public function fire()
    {
        $deviceRepairList = $this->deviceRepairService->all();
        $deviceTypeList = $this->deviceTypeService->all();

        $this->loopThroughItems($deviceRepairList, 'product_id');
        $this->loopThroughItems($deviceTypeList, 'category_id');
    }

    protected function loopThroughItems($modelList, $modelName)
    {
        foreach ($modelList as $model)
        {
            $urlAlias = DB::select(DB::raw("SELECT * FROM `biffy`.`url_alias` WHERE `model` = '{$modelName}' AND `model_id` = {$model->id}"));

            if (count($urlAlias) == 0)
            {
                $seo = $this->nameToSeo($model->name);
                $sql = "INSERT INTO `biffy`.`url_alias`(`model`, `model_id`, `keyword`) VALUES ('{$modelName}',{$model->id},'{$seo}')";

                echo("{$sql}\n");

                DB::statement(DB::raw($sql));
            }
        }
    }

    protected function nameToSeo($name)
    {
        $seo = str_replace(' ', '-', $name);
        $seo = preg_replace('/[^A-Za-z0-9\-]/', '', $seo);
        $seo = strtolower($seo);
        $seo = str_replace('--', '-', $seo);

        return $seo;
    }

//    public function fire()
//    {
//        DB::statement(DB::raw('TRUNCATE url_alias'));
//
//        //1) All of the devices which have no children need a url alias
//        $topLevelDeviceList = DB::select(DB::raw('SELECT * FROM `device_types` WHERE `top` = 1'));
//
//        foreach ($topLevelDeviceList as $topLevelDevice)
//        {
//            if ($topLevelDevice->id == 59)
//            {
//                continue;
//            }
//
//            $this->checkChildren($topLevelDevice, $topLevelDevice->id);
//        }
//
//        //2) All of the repairs need a url alias
//        $deviceRepairList = $this->deviceRepairService->all();
//
//        foreach ($deviceRepairList as $deviceRepair)
//        {
////            echo("{$deviceRepair->name}\n");
//            if ($deviceRepair->name == ' Repair')
//            {
//                echo("{$deviceRepair->id}\n");
//            }
//            else
//            {
//                $urlAlias = str_replace('', '&amp; ', strtolower($deviceRepair->name));
//                $urlAlias = str_replace(' ', '-', $urlAlias);
//                DB::statement(DB::raw("INSERT INTO `url_alias`(`model`, `model_id`, `keyword`) VALUES ('product_id',{$deviceRepair->id},'{$urlAlias}')"));
//            }
//        }
//    }
//
//    protected function checkChildren($parent, $topLevelDeviceId)
//    {
//        $childList = $this->deviceTypeService->clearFilters()->clearQuery()->filterBy(['parent_id' => $parent->id])->get();
//
//        foreach ($childList as $child)
//        {
//            if ($child->pos_selectable == true)
//            {
//                //If a DeviceType has no children, it is Level 3.  Sort by Popular Family and Date Released
//                $urlAlias = str_replace(' ', '-', strtolower($child->name));
//                DB::statement(DB::raw("INSERT INTO `url_alias`(`model`, `model_id`, `keyword`) VALUES ('category_id',{$child->id},'{$urlAlias}')"));
//            } else {
//                //If a Device Type is not root, but has children, it is Level 2.  Sort by view count over the last 30 days
//                $this->checkChildren($child, $topLevelDeviceId);
//            }
//        }
//    }

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