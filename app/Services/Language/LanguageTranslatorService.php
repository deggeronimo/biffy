<?php namespace Biffy\Services\Language;

use Biffy\Entities\Language\LanguageRepositoryInterface;
use Biffy\Entities\LanguageKey\LanguageKeyRepositoryInterface;
use Biffy\Entities\LanguageString\LanguageStringRepositoryInterface;

class LanguageTranslatorService
{
    const KEY_NOT_FOUND = 'Key not found';
    const LANGUAGE_NOT_FOUND = 'Language not found';

    protected $currentLanguageCode = 'en';
    protected $currentLanguageId = 1;

    protected $languageRepo;
    protected $languageKeyRepo;
    protected $languageStringRepo;

    public function __construct(LanguageRepositoryInterface $languageRepo, LanguageKeyRepositoryInterface $languageKeyRepo,
                                LanguageStringRepositoryInterface $languageStringRepo)
    {
        $this->languageRepo = $languageRepo;
        $this->languageKeyRepo = $languageKeyRepo;
        $this->languageStringRepo = $languageStringRepo;

        $this->currentLanguageCode = \Auth::user() ? \Auth::user()->language() : \Request::header('X-Language-Code');
        $this->currentLanguageId = $this->languageRepo->findFirstBy('lang', $this->currentLanguageCode)->id;
    }

    public function generateKey($model, $id, $attribute)
    {
        $attribute = snake_case($attribute);
        $modelClassName = snake_case(join('', array_slice(explode('\\', get_class($model)), -1)));

        return "{$modelClassName}_{$id}_{$attribute}";
    }

    public function get($key, $languageId = null)
    {
        $languageId = $languageId ?: $this->languageId();

        $languageKey = $this->languageKeyRepo->findFirstBy('key', $key);

        if (is_null($languageKey))
        {
            return self::KEY_NOT_FOUND;
        }

        $languageKeyId = $languageKey->id;

        $languageString = $this->languageStringRepo->firstByAttributes([
            'language_id' => $languageId,
            'language_key_id' => $languageKeyId
        ]);

        return trim(!is_null($languageString) ? $languageString->string : self::LANGUAGE_NOT_FOUND);
    }

    public function getWithoutKey($model, $id, $attribute, $languageId = null)
    {
        $key = $this->generateKey($model, $id, $attribute);
        return $this->get($key, $languageId);
    }

    public function languageCode()
    {
        return $this->currentLanguageCode;
    }

    public function languageId()
    {
        return $this->currentLanguageId;
    }


    /**
     * @deprecated
     * @param $key
     * @return string
     */
    public function string($key)
    {
        return $this->get($key);
    }

    public function set($key, $value, $languageId = null)
    {
        $languageId = $languageId ?: $this->languageId();

        $languageKey = $this->languageKeyRepo->findFirstBy('key', $key);

        if (is_null($languageKey))
        {
            return;
        }

        $languageKeyId = $languageKey->id;

        $languageString = $this->languageStringRepo->firstByAttributes([
            'language_id' => $languageId,
            'language_key_id' => $languageKeyId
        ]);

        $languageString->string = trim($value);
        $languageString->save();
    }

    public function setWithoutKey($model, $id, $attribute, $value, $languageId = null)
    {
        $key = $this->generateKey($model, $id, $attribute);
        $this->set($key, $value, $languageId);
    }
}
