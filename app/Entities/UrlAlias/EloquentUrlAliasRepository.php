<?php namespace Biffy\Entities\UrlAlias;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentUrlAliasRepository extends EloquentAbstractRepository implements UrlAliasRepositoryInterface
{
    public function __construct(UrlAlias $model)
    {
        $this->model = $model;
    }

    public function nameToSeo($name)
    {
        $seo = str_replace(' ', '-', $name);
        $seo = preg_replace('/[^A-Za-z0-9\-]/', '', $seo);
        $seo = strtolower($seo);
        $seo = str_replace('--', '-', $seo);

        return $seo;
    }
}