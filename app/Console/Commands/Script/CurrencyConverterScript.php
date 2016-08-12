<?php namespace Biffy\Console\Commands\Script;

use Biffy\Entities\DeviceRepair\DeviceRepair;
use Biffy\Entities\Language\Language;
use Biffy\Entities\LanguageKey\LanguageKeyRepositoryInterface;
use Biffy\Entities\LanguageString\LanguageStringRepositoryInterface;
use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\Language\LanguageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CurrencyConverterScript extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'script:currency';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

    protected $deviceRepairIdList = [];

    protected $deviceRepairModel;
    protected $languageModel;

    protected $languageList;

    protected $languageService;

    protected $languageKeyRepo;
    protected $languageStringRepo;

    protected $conversionRatios = [
        1 => 1.0, 1.2435, 6.348, 1.2435
    ];

    /**
     * Create a new command instance.
     *
     * @param DeviceRepair $deviceRepairModel
     * @param Language $languageModel
     * @param LanguageService $languageService
     * @param LanguageKeyRepositoryInterface $languageKeyRepo
     * @param LanguageStringRepositoryInterface $languageStringRepo
     */
    public function __construct(DeviceRepair $deviceRepairModel, Language $languageModel, LanguageService $languageService,
                                LanguageKeyRepositoryInterface $languageKeyRepo, LanguageStringRepositoryInterface $languageStringRepo)
    {
        parent::__construct();

        $this->deviceRepairModel = $deviceRepairModel;
        $this->languageModel = $languageModel;

        $this->languageKeyRepo = $languageKeyRepo;
        $this->languageStringRepo = $languageStringRepo;

        $this->languageService = $languageService;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->languageList = $this->languageService->all();

        $productList = DB::select(DB::raw("SELECT `product_id`, `price` FROM `ubif_website_v2`.`product`"));

        foreach ($productList as $product)
        {
            if ($product->price == '0.01')
            {
                $key = LanguageTranslator::generateKey($this->deviceRepairModel, $product->product_id, 'estimated_cost');

                foreach ($this->languageList as $language)
                {
                    LanguageTranslator::set($key, '0.01', $language->id);
                }
            }
        }

//        $this->deviceRepairIdList = $this->deviceRepairModel->lists('id');
//        $this->languageList = $this->languageModel->lists('id');
//
//        foreach ($this->deviceRepairIdList as $deviceRepairId)
//        {
//            $languageKey = LanguageTranslator::generateKey($this->deviceRepairModel, $deviceRepairId, 'estimated_cost');
//            $languageKeyId = $this->languageKeyRepo->firstByAttributes(['key' => $languageKey])->id;
//
//            $languageStringList = $this->languageStringRepo->findAllBy('language_key_id', $languageKeyId);
//
//            $baseEstimatedCost = 0;
//
//            foreach ($languageStringList as $languageString)
//            {
//                if ($languageString->language_id == 1)
//                {
//                    $baseEstimatedCost = intval($languageString->string);
//                    break;
//                }
//            }
//
//            foreach ($languageStringList as $languageString)
//            {
//                if ($baseEstimatedCost <= 0)
//                {
//                    $newPrice = 0;
//                }
//                else
//                {
//                    $newPrice = $baseEstimatedCost * $this->conversionRatios[$languageString->language_id];
//                    $newPrice = round($newPrice / 5) * 5 - 0.01;
//                }
//
//                $languageString->string = number_format($newPrice, 2);
//                $languageString->save();
//            }
//        }
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
