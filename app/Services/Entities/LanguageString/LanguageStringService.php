<?php namespace Biffy\Services\Entities\LanguageString;

use Biffy\Entities\LanguageKey\LanguageKeyRepositoryInterface;
use Biffy\Entities\LanguageString\LanguageStringRepositoryInterface;
use Biffy\Services\Entities\Language\LanguageService;
use Biffy\Services\Entities\Service;

class LanguageStringService extends Service
{
    protected $languageService;
    protected $languageKeyRepo;

    public function __construct(LanguageStringRepositoryInterface $repo, LanguageService $languageService,
                                LanguageKeyRepositoryInterface $languageKeyRepo)
    {
        $this->repo = $repo;

        $this->languageService = $languageService;
        $this->languageKeyRepo = $languageKeyRepo;
    }

    public function copyLanguageStrings($stringColumns, $model, $attributes)
    {
        foreach ($stringColumns as $string)
        {
            $languageStringId = $model->$string;

            $languageString = $this->repo->find($languageStringId);

            $newLanguageStringId = $this->repo->create($languageString->toArray())->id;

            $attributes[$string] = $newLanguageStringId;
        }

        return $attributes;
    }

    public function getByKeyId($keyId)
    {
        $this->repo->addFilter('key_id', $keyId)->addFilter('lang_id', $this->languageService->languageId());

        return $this->repo->get();
    }

    public function update($id, $attributes)
    {
        $result = $this->repo->update($id, $attributes);

        $languageString = $this->repo->find($id);

        $key = $this->languageKeyRepo->find($languageString->language_key_id)->key;

        $this->hookLanguageStringUpdate($key, $attributes['string'], $languageString->language_id);

        return $result;
    }

    private function hookLanguageStringUpdate($key, $value, $languageId)
    {
        $keyList = explode('_', $key);

        $i = 0; $modelName = '';
        while(!is_numeric($keyList[$i]))
        {
            $modelName = $i == 0 ? $keyList[$i] : "{$modelName}_{$keyList[$i]}";
            $i ++;
        }

        $id = intval($keyList[$i]);
        $i ++;

        $attributeName = $keyList[$i];
        $i ++;

        while($i < count($keyList))
        {
            $attributeName = "{$attributeName}_{$keyList[$i]}";
            $i ++;
        }

        $modelName = ucfirst(camel_case($modelName));
        $modelName = "Biffy\\Services\\Entities\\{$modelName}\\{$modelName}Service";

        $service = app($modelName);

        $service->languageStringUpdated($id, $attributeName, $value, $languageId);
    }
}