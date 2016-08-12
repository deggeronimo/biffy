<?php namespace Biffy\Services\Entities\UrlAlias;

use Biffy\Entities\UrlAlias\UrlAliasRepositoryInterface;
use Biffy\Services\Entities\Service;

class UrlAliasService extends Service
{
    public function __construct(UrlAliasRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}