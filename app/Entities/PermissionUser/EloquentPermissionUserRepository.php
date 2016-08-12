<?php namespace Biffy\Entities\PermissionUser;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentPermissionUserRepository extends EloquentAbstractRepository implements PermissionUserRepositoryInterface
{
    /**
     * @param PermissionUser $model
     */
    public function __construct(PermissionUser $model)
    {
        $this->model = $model;
    }

    public function getGlobalPermissions($userId)
    {
        return $this->model->select('permission_id')->where('user_id', '=', $userId)->whereNull('store_id')->get()->lists('permission_id');
    }

    public function getStorePermissions($userId, $storeId)
    {
        return $this->model->select('permission_id')->where('user_id', '=', $userId)->where('store_id', '=', $storeId)->get()->lists('permission_id');
    }

    /**
     * @param int $userId
     * @param array $permissionIds
     * @return mixed
     */
    public function setGlobalPermissions($userId, $permissionIds)
    {
        // remove user's global permissions
        $this->model->where('user_id', '=', $userId)->whereNull('store_id')->delete();

        // insert global permissions
        $data = [];
        foreach ($permissionIds as $permissionId) {
            $data[] = [
                'user_id' => $userId,
                'store_id' => null,
                'permission_id' => $permissionId
            ];
        }

        if (count($data)) {
            $this->model->insert($data);
        }
    }

    /**
     * @param int $userId
     * @param int $storeId
     * @param array $permissionIds
     * @return mixed
     */
    public function setStorePermissions($userId, $storeId, $permissionIds)
    {
        // remove user's permissions for given store
        $this->model->where('user_id', '=', $userId)->where('store_id', '=', $storeId)->delete();

        // insert permissions for given store
        $data = [];
        foreach ($permissionIds as $permissionId) {
            $data[] = [
                'user_id' => $userId,
                'store_id' => $storeId,
                'permission_id' => $permissionId
            ];
        }

        if (count($data)) {
            $this->model->insert($data);
        }
    }
}