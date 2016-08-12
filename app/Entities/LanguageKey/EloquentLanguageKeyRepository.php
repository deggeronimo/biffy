<?php namespace Biffy\Entities\LanguageKey;

use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Entities\Language\Language;

class EloquentLanguageKeyRepository extends EloquentAbstractRepository implements LanguageKeyRepositoryInterface
{
    public function __construct(LanguageKey $model, Language $languageModel)
    {
        $this->model = $model;

        $this->languageModel = $languageModel;
    }

    public function create($attributes)
    {
        $result = parent::create($attributes);

        $languageIdList = $this->languageModel->lists('id');

        $result->languages()->attach($languageIdList, [
            'string' => ''
        ]);

        return $result;
    }
}