<?php namespace Biffy\Services;

use Biffy\Facades\LanguageTranslator;

trait LanguageKeyCreator
{
    protected $languageKeyService;

    public function create($attributes)
    {
        $attributes = $this->beforeCreate($attributes);

        $result = parent::create($attributes);

        if (!is_null($this->languageKeyService))
        {
            $languageKeyList = $this->repo->getModelStrings();

            foreach ($languageKeyList as $languageKey)
            {
                $languageKeyResult = $this->languageKeyService->create([
                    'key' => LanguageTranslator::generateKey($result, $result->id, $languageKey)
                ]);

                $languageKey = snake_case($languageKey);
                $attributeStringIdName = "{$languageKey}_language_key_id";

                $result->$attributeStringIdName = $languageKeyResult->id;
                $result->save();
            }
        }

        $this->afterCreate($result);

        return $this->find($result->id);
    }

    public function beforeCreate($attributes)
    {
        return $attributes;
    }

    public function afterCreate($result)
    {

    }
}