<?php namespace Biffy\Entities\Group;

use Biffy\Entities\AbstractRepositoryInterface;

interface GroupRepositoryInterface extends AbstractRepositoryInterface
{
    public function edit($groupId, $name, $email, $isStore = null);

    public function addUser($groupId, $userId);

    public function removeUser($groupId, $userId);

    public function getEmailById($groupId);
} 