<?php namespace Biffy\Services\Entities\LanguageKey;

use Biffy\Entities\LanguageKey\LanguageKeyRepositoryInterface;
use Biffy\Services\Entities\LanguageString\LanguageStringService;
use Biffy\Services\Entities\Service;

class LanguageKeyService extends Service
{
    protected $languageStringService;

    public function __construct(LanguageKeyRepositoryInterface $repo, LanguageStringService $languageStringService)
    {
        $this->repo = $repo;

        $this->languageStringService = $languageStringService;
    }

    public function create($attributes, $source = null)
    {
        $result = $this->repo->create($attributes);

        if (!is_null($source))
        {
            $sourceLanguageKey = $this->findFirstBy('key', '=', $source);

            if (!is_null($sourceLanguageKey))
            {
                $sourceLanguageKeyId = $sourceLanguageKey->id;

                $destinationLanguageStringList = $this->languageStringService->findAllBy('language_key_id', '=', $result->id);
                $sourceLanguageStringList = $this->languageStringService->findAllBy('language_key_id', '=', $sourceLanguageKeyId);

                foreach ($sourceLanguageStringList as $sourceLanguageString)
                {
                    foreach ($destinationLanguageStringList as $destLanguageString)
                    {
                        if ($sourceLanguageString->language_id == $destLanguageString->language_id)
                        {
                            $destLanguageString->string = $sourceLanguageString->string;
                            $destLanguageString->save();

                            break;
                        }
                    }
                }
            }
        }

        return $result;
    }
}