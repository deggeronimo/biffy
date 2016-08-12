<?php

use Biffy\Entities\Language\Language;
use Biffy\Facades\LanguageTranslator;

class AbstractArraySeeder extends \Illuminate\Database\Seeder
{
    protected $itemList = [];

    protected $languageMap = null;

    protected $model = null;

    protected $service = null;

    protected $stringList = [];

    private function generateLanguageMap()
    {
        $languageList = Language::all();
        $this->languageMap = [];

        foreach ($languageList as $language)
        {
            $this->languageMap[$language->lang] = $language->id;
        }
    }

    public function run()
    {
        $useServiceOrModel = $this->service ? $this->service : $this->model;

        if (is_null($useServiceOrModel))
        {
            return;
        }

        if (is_null($this->languageMap))
        {
            $this->generateLanguageMap();
        }

        foreach ($this->itemList as $item)
        {
            $useServiceOrModel->create($item);
        }

        foreach ($this->stringList as $string)
        {
            $stringValues = $string['values'];

            foreach ($stringValues as $key => $stringValue)
            {
                $languageKey = LanguageTranslator::generateKey($this->model, $string['id'], $key);

                foreach ($stringValue as $language => $value)
                {
                    LanguageTranslator::set($languageKey, $value, $this->languageMap[$language]);
                }
            }
        }

        $this->complete();
    }

    public function complete()
    {
    }
}