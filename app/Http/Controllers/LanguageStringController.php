<?php namespace Biffy\Http\Controllers;

use Biffy\Http\Requests\AbstractFormRequest;
use Biffy\Services\Entities\LanguageKey\LanguageKeyService;
use Biffy\Services\Entities\LanguageString\LanguageStringService;

class LanguageStringController extends ApiController
{
    public function __construct(LanguageStringService $service, LanguageKeyService $languageKeyService)
    {
        $this->service = $service;

        $this->languageKeyService = $languageKeyService;
    }

    /**
     * @param $languageKeyValue
     * @return mixed
     */
    public function index($languageKeyValue)
    {
        $languageKey = $this->languageKeyService->findFirstBy('key', $languageKeyValue);

        $result = $this->service->findAllBy('language_key_id', '=', $languageKey->id);

        return $this->data($result->toArray())->respond();
    }

    public function update($languageKeyValue, $id, AbstractFormRequest $request)
    {
        $stringList = $request->input('strings');

        foreach ($stringList as $string)
        {
            $this->service->update($string['id'], $string);
        }

        return $this->messages('update', 'Updated successfully!')->statusUpdated()->respond();
    }
}