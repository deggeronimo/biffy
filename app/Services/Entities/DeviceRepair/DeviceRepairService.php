<?php namespace Biffy\Services\Entities\DeviceRepair;

use Biffy\Entities\DeviceRepair\DeviceRepairRepositoryInterface;
use Biffy\Entities\DeviceRepairType\DeviceRepairType;
use Biffy\Entities\Language\Language;
use Biffy\Entities\UrlAlias\UrlAliasRepositoryInterface;
use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\DeviceRepairType\DeviceRepairTypeService;
use Biffy\Services\Entities\LanguageKey\LanguageKeyService;
use Biffy\Services\Entities\Service;
use Biffy\Services\LanguageKeyCreator;

class DeviceRepairService extends Service
{
    protected $deviceRepairTypeService;

    protected $urlAliasRepo;

    protected $languageList;

    use LanguageKeyCreator;

    public function __construct(DeviceRepairRepositoryInterface $repo, DeviceRepairTypeService $deviceRepairTypeService,
                                LanguageKeyService $languageKeyService, UrlAliasRepositoryInterface $urlAliasRepo)
    {
        $this->repo = $repo;

        $this->languageKeyService = $languageKeyService;
        $this->deviceRepairTypeService = $deviceRepairTypeService;

        $this->urlAliasRepo = $urlAliasRepo;
    }

    public function afterCreate($result)
    {
        // moved from constructor, was hindering migrate
        $languageService = app('Biffy\Services\Entities\Language\LanguageService');
        $this->languageList = $languageService->all(); // todo cache if necessary

        $deviceRepairTemplate = $this->deviceRepairTypeService->find($result->device_repair_type_id);

        $attributeList = $this->repo->getModelStrings();

        $deviceTypeName = implode(' ', array_slice(explode(' ', LanguageTranslator::getWithoutKey($result->deviceType, $result->deviceType->id, 'name', 1)), 0, -1));

        foreach ($attributeList as $attribute)
        {
            foreach ($this->languageList as $language)
            {
                $templateValue = LanguageTranslator::getWithoutKey($deviceRepairTemplate, $deviceRepairTemplate->id, $attribute, $language->id);

                if ($attribute == 'name')
                {
                    switch ($language->id)
                    {
                        case Language::CA_FR:
                            $templateValue = "{$templateValue} pour {$deviceTypeName}";
                            break;
                        default:
                            $templateValue = "{$deviceTypeName} {$templateValue}";
                            break;
                    }
                }
                else if ($attribute == 'web_description')
                {
                    $templateValue = str_replace('$device$', $deviceTypeName, $templateValue);
                }

                LanguageTranslator::setWithoutKey($result, $result->id, $attribute, $templateValue, $language->id);
            }
        }

        $result->sort_order = $deviceRepairTemplate->sort_order;
        $result->save();

        $name = LanguageTranslator::getWithoutKey($result, $result->id, 'name');

        $seo = $this->urlAliasRepo->nameToSeo($name);

        $this->urlAliasRepo->create([
            'model' => 'product_id',
            'model_id' => $result->id,
            'keyword' => $seo
        ]);
    }

    protected function languageStringUpdated($id, $attributeName, $value, $languageId)
    {
        if ($attributeName == 'name' && $languageId == 1)
        {
            $urlAlias = $this->urlAliasRepo->firstByAttributes([
                'model' => 'product_id',
                'model_id' => $id
            ]);

            $urlAlias->keyword = self::nameToSeo($value);
            $urlAlias->save();
        }
        else if ($attributeName == 'web_description')
        {
            $deviceRepair = $this->find($id);
            $deviceType = $deviceRepair->deviceType;

            $deviceName = LanguageTranslator::getWithoutKey($deviceType, $deviceType->id, 'name');

            $templateValue = str_replace('$device$', $deviceName, $value);

            LanguageTranslator::setWithoutKey($deviceRepair, $deviceRepair->id, 'web_description', $templateValue, $languageId);
        }
    }
}