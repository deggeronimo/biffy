<?php namespace Biffy\Entities\UrlAlias;

use Biffy\Entities\AbstractRepositoryInterface;

interface UrlAliasRepositoryInterface extends AbstractRepositoryInterface
{
    public function nameToSeo($name);
}