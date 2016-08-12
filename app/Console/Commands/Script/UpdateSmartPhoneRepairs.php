<?php namespace Biffy\Console\Commands\Script;

use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\DeviceRepair\DeviceRepairService;
use Biffy\Services\Entities\DeviceRepairType\DeviceRepairTypeService;
use Biffy\Services\Entities\DeviceType\DeviceTypeService;
use Biffy\Services\Entities\Language\LanguageService;
use Biffy\Services\Entities\LanguageString\LanguageStringService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateSmartPhoneRepairs extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'script:updaterepairs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    protected $deviceRepairService;
    protected $deviceRepairTypeService;
    protected $deviceTypeService;

    protected $languageService;
    protected $languageStringService;

    protected $deviceTypeList = [];
    protected $bottomDeviceTypeList = [];

    protected $repairTypeIdList = [ 2 ];

    /**
     * Create a new command instance.
     *
     * @param DeviceRepairService $deviceRepairService
     * @param DeviceRepairTypeService $deviceRepairTypeService
     * @param DeviceTypeService $deviceTypeService
     * @param LanguageService $languageService
     * @param LanguageStringService $languageStringService
     * @internal param DeviceTypeService $deviceTypeService
     */
    public function __construct(DeviceRepairService $deviceRepairService, DeviceRepairTypeService $deviceRepairTypeService,
                                DeviceTypeService $deviceTypeService, LanguageService $languageService, LanguageStringService $languageStringService)
    {
        parent::__construct();

        $this->deviceRepairService = $deviceRepairService;
        $this->deviceRepairTypeService = $deviceRepairTypeService;
        $this->deviceTypeService = $deviceTypeService;

        $this->languageService = $languageService;
        $this->languageStringService = $languageStringService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        foreach ($this->repairTypeIdList as $repairTypeId)
        {
            $deviceRepairList = $this->deviceRepairService->findAllBy('device_repair_type_id', $repairTypeId);
            $deviceRepairType = $this->deviceRepairTypeService->find($repairTypeId);
            $languageList = $this->languageService->all();

            $webDescriptionList = [];

            foreach ($languageList as $language)
            {
                $webDescriptionList[$language->id] = LanguageTranslator::getWithoutKey($deviceRepairType, $deviceRepairType->id, 'web_description', $language->id);
            }

            foreach ($deviceRepairList as $deviceRepair)
            {
                foreach ($languageList as $language)
                {
                    $deviceType = $deviceRepair->deviceType;

                    if (is_null($deviceType))
                    {
                        $sql = "select `string` from `device_types` join `language_strings` on `language_strings`.`language_key_id` = `device_types`.`name_language_key_id` where `language_strings`.`language_id` = {$language->id} and `device_types`.`id` = {$deviceRepair->device_type_id}";

                        $deviceTypeName = DB::select(DB::raw($sql))[0]->string;
                    }
                    else
                    {
                        $deviceTypeName = $deviceType->name;
                    }

                    echo("Fixing {$deviceTypeName}\n");

                    $webDescription = str_replace('$device$', $deviceTypeName, $webDescriptionList[$language->id]);

                    LanguageTranslator::setWithoutKey($deviceRepair, $deviceRepair->id, 'web_description', $webDescription, $language->id);
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