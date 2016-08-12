<?php namespace Biffy\Entities\Group;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentGroupRepository
 * @package Biffy\Entities\Group
 */
class EloquentGroupRepository extends EloquentAbstractRepository implements GroupRepositoryInterface
{
    /**
     * @param Group $model
     */
    public function __construct(Group $model)
    {
        $this->model = $model;
    }

    /**
     * @param $groupId
     * @param $name
     * @param $email
     * @param null $isStore
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function edit($groupId, $name, $email, $isStore = null)
    {
        $group = $this->find($groupId);
        $group->name = $name;
        $group->email = $email;

        if (!is_null($isStore)) {
            $group->is_store = $isStore;
        }

        $group->save();
        return $group;
    }

    /**
     * @param $groupId
     * @param $userId
     */
    public function addUser($groupId, $userId)
    {
        $this->find($groupId)->users()->attach($userId);
    }

    /**
     * @param $groupId
     * @param $userId
     */
    public function removeUser($groupId, $userId)
    {
        $this->find($groupId)->users()->attach($userId);
    }

    /**
     * @param $groupId
     * @return mixed
     */
    public function getEmailById($groupId)
    {
        $group = $this->find($groupId);
        return $group->email;
    }
}