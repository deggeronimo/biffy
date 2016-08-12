<?php namespace Biffy\Entities\WebsiteFilter;

use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Facades\LanguageTranslator;

class EloquentWebsiteFilterRepository extends EloquentAbstractRepository implements WebsiteFilterRepositoryInterface
{
    protected $filters = [
        'search' => ['sls.string like ?', '%:value%']
    ];

    public function __construct(WebsiteFilter $model)
    {
        $this->model = $model;

        $this->with([ 'websiteFilterGroup' ]);
    }

    public function get($columns = ['*'])
    {
        if (is_null($this->query))
        {
            $this->query = $this->model->strings();
            $this->query->with($this->with);
        }

        if (count($this->filterBy) > 0)
        {
            $this->query
                ->leftJoin('language_strings as sls', 'name_language_key_id', '=', 'sls.language_key_id')
                ->addSelect('sls.string')
                ->where('sls.language_id', '=', LanguageTranslator::languageId());
        }

        return parent::get($columns);
    }
}