<?php namespace Biffy\Entities\Config;

use Biffy\Entities\AbstractRepositoryInterface;

interface ConfigRepositoryInterface extends AbstractRepositoryInterface
{
    public function listNames();

    public function dataForCaching();

} 