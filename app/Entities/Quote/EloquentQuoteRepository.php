<?php namespace Biffy\Entities\Quote;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentQuoteRepository extends EloquentAbstractRepository implements QuoteRepositoryInterface
{
    public function __construct(Quote $model)
    {
        $this->model = $model;
    }
}