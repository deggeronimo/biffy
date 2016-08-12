<?php namespace Biffy\Services\Entities\Group;

use Biffy\Entities\Group\GroupRepositoryInterface;
use Biffy\Services\Entities\Service;

/**
 * Class GroupService
 * @package Biffy\Services\Entities\Group
 */
class GroupService extends Service
{
    /**
     * @param GroupRepositoryInterface $repo
     */
    public function __construct(GroupRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function addUser($groupId, $userId)
    {
        // todo use or remove is_store on group_user table
        return $this->repo->addUser($groupId, $userId);
    }

    public function removeUser($groupId, $userId)
    {
        return $this->repo->removeUser($groupId, $userId);
    }

    public function getEmailById($groupId)
    {
        return $this->repo->find($groupId);
    }

    /**
     * @param $group
     * @return int
     */
    public function createFromGoogle($group)
    {
        $createdGroup = $this->create([
                'name' => str_replace('Group ', '', $group->name),
                'email' => $group->email,
                'is_store' => $this->isStoreGroup($group->email)
            ]);

        return $createdGroup->id;
    }

    public function isStoreGroup($email)
    {
        return stripos($email, 'group_') !== false;
    }
}