<?php namespace Biffy\Console\Commands\Script;

use Biffy\Services\Entities\DeviceRepair\DeviceRepairService;
use Biffy\Services\Entities\DeviceType\DeviceTypeService;
use DB;
use Illuminate\Console\Command;

class ImportKeywords extends Command
{
    protected $name = 'script:keywords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    protected $deviceRepairService;
    protected $deviceTypeService;

    private $category_idList = [];
    private $product_idList = [];

    private $deviceTypeIdMap = [
        176 => 1,

        62 => 9,
        64 => 10,
        230 => 11,
        65 => 46,

        47 => 47,
        48 => 48,
        97 => 97,
        98 => 98,

        229 => 176,
        66 => 177
    ];

    public function __construct(DeviceTypeService $deviceTypeService, DeviceRepairService $deviceRepairService)
    {
        parent::__construct();

        $this->deviceRepairService = $deviceRepairService;
        $this->deviceTypeService = $deviceTypeService;
    }

    public function fire()
    {
        DB::statement(DB::raw("TRUNCATE TABLE `biffy`.`url_alias`"));

        $this->category_idList = DB::select(DB::raw("SELECT * FROM `ubif_website_v2`.`url_alias` WHERE `query` LIKE '%category_id=%'"));
        $this->product_idList = DB::select(DB::raw("SELECT * FROM `ubif_website_v2`.`url_alias` WHERE `query` LIKE '%product_id=%'"));

        foreach ($this->category_idList as $category)
        {
            $categoryId = explode('=', $category->query)[1];

            if (isset($this->deviceTypeIdMap[$categoryId]))
            {
                $categoryId = $this->deviceTypeIdMap[$categoryId];
            }

            DB::statement(DB::raw("INSERT INTO `biffy`.`url_alias` (`id`, `model`, `model_id`, `keyword`) VALUES ({$category->url_alias_id}, 'category_id', {$categoryId}, '{$category->keyword}')"));
        }

        foreach ($this->product_idList as $product)
        {
            $productId = explode('=', $product->query)[1];

            DB::statement(DB::raw("INSERT INTO `biffy`.`url_alias` (`id`, `model`, `model_id`, `keyword`) VALUES ({$product->url_alias_id}, 'product_id', {$productId}, '{$product->keyword}')"));
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