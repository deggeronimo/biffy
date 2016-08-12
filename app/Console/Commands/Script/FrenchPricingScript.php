<?php namespace Biffy\Console\Commands\Script;

use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\DeviceRepair\DeviceRepairService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FrenchPricingScript extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'script:frenchpricing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    protected $deviceRepairList = [];

    /**
     * Create a new command instance.
     * @param DeviceRepairService $deviceRepairService
     */
    public function __construct(DeviceRepairService $deviceRepairService)
    {
        parent::__construct();

        $this->deviceRepairService = $deviceRepairService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        //NOTE: remove the collation when running on local machine
        $this->deviceRepairList = DB::select(DB::raw("select `device_repairs`.`id`, COALESCE(`dp`.`price`, 0) AS `price` from `device_repairs` join `items` on `items`.`id` = `device_repairs`.`item_id` left join `ubif_distro_dev`.`distroproduct` as `dp` on `dp`.`model` COLLATE utf8_unicode_ci = `items`.`item_number` where `device_repairs`.`item_id` is not null and `device_repairs`.`deleted_at` is null group by `device_repairs`.`id` order by `device_repairs`.`id`"));

        foreach ($this->deviceRepairList as $deviceRepair)
        {
            $deviceRepairCopy = $this->deviceRepairService->findFirstBy('id', $deviceRepair->id);

            if (is_null($deviceRepairCopy))
            {
                echo("Could not find:\n");
                dd($deviceRepair->id);
            }

            $partCost = floatval($deviceRepair->price);

            $enEstimatedCost = floatval(LanguageTranslator::getWithoutKey($deviceRepairCopy, $deviceRepairCopy->id, 'estimated_cost', 1));

            if ($enEstimatedCost == 0)
            {
                print_r([
                    'item_name' => $deviceRepairCopy->item->name,
                    'item_price' => $partCost
                ]);

                continue;
            }
            else if ($enEstimatedCost == 0.01)
            {
                print_r([
                    'message' => 'Call for Quote'
                ]);

                continue;
            }

            $caNewPrice = $enEstimatedCost - $partCost + ($partCost * 1.2435);
            $caNewPrice = round($caNewPrice / 5) * 5 - 0.01;

            $ttNewPrice = $enEstimatedCost * 9;
            $ttNewPrice = round($ttNewPrice / 5) * 5 - 0.01;

            print_r([
                'item_name' => $deviceRepairCopy->item->name,
                'item_price' => $partCost,
                'old_price' => $enEstimatedCost,
                'new_prices' => [
                    'en' => number_format($enEstimatedCost, 2),
                    'ca' => number_format($caNewPrice, 2),
                    'ca_fr' => number_format($caNewPrice, 2),
                    'tt' => number_format($ttNewPrice, 2)
                ]
            ]);

//            LanguageTranslator::setWithoutKey($deviceRepairCopy, $deviceRepairCopy->id, 'estimated_cost', number_format($enEstimatedCost, 2), 1);
//            LanguageTranslator::setWithoutKey($deviceRepairCopy, $deviceRepairCopy->id, 'estimated_cost', number_format($caNewPrice, 2), 2);
            LanguageTranslator::setWithoutKey($deviceRepairCopy, $deviceRepairCopy->id, 'estimated_cost', number_format($ttNewPrice, 2), 3);
//            LanguageTranslator::setWithoutKey($deviceRepairCopy, $deviceRepairCopy->id, 'estimated_cost', number_format($caNewPrice, 2), 4);
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
