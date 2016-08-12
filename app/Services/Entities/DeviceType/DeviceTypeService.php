<?php namespace Biffy\Services\Entities\DeviceType;

use Biffy\Entities\DeviceType\DeviceTypeRepositoryInterface;
use Biffy\Entities\UrlAlias\UrlAliasRepositoryInterface;
use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\LanguageKey\LanguageKeyService;
use Biffy\Services\Entities\Service;
use Biffy\Services\LanguageKeyCreator;

class DeviceTypeService extends Service
{
    use LanguageKeyCreator;

    protected $urlAliasRepo;

    public function __construct(DeviceTypeRepositoryInterface $repo, LanguageKeyService $languageKeyService, UrlAliasRepositoryInterface $urlAliasRepo)
    {
        $this->repo = $repo;

        $this->languageKeyService = $languageKeyService;

        $this->urlAliasRepo = $urlAliasRepo;
    }

    public function afterCreate($result)
    {
        $name = LanguageTranslator::getWithoutKey($result, $result->id, 'name');

        $seo = $this->urlAliasRepo->nameToSeo($name);

        $this->urlAliasRepo->create([
            'model' => 'category_id',
            'model_id' => $result->id,
            'keyword' => $seo
        ]);
    }

    public function getDeviceChecklist($deviceTypeId)
    {
        return $this->repo->getDeviceChecklist($deviceTypeId);
    }

    protected function languageStringUpdated($id, $attributeName, $value, $languageId)
    {
        if ($attributeName == 'name' && $languageId == 1)
        {
            $urlAlias = $this->urlAliasRepo->firstByAttributes([
                'model' => 'category_id',
                'model_id' => $id
            ]);

            $urlAlias->keyword = self::nameToSeo($value);
            $urlAlias->save();
        }
    }
}