<?php namespace Biffy\Entities\LanguageString;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentLanguageStringRepository extends EloquentAbstractRepository implements LanguageStringRepositoryInterface
{
    protected $filters = [
        'language_key_id' => [ 'language_key_id = ?', ':value' ],
        'language_id' => [ 'language_id = ?', ':value' ]
    ];

    public function __construct(LanguageString $model)
    {
        $this->model = $model;
    }
}