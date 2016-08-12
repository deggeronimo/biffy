<?php namespace Biffy\Entities\Permission;

use Biffy\Entities\AbstractRepositoryInterface;

interface PermissionRepositoryInterface extends AbstractRepositoryInterface
{
    public function edit($id, $name);
} 