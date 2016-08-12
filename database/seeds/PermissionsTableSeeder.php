<?php

use Biffy\Entities\Permission\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    protected $globalPermissions = [
        '' => [
            'admin' => '',
            'users' => '',
            'permissions' => '',
            'roles' => '',
            'groups' => '',
            'config' => '',
            'items' => '',
            'settings' => '',

            'data-management' => '',
            'checklists' => '',
            'website' => '',
            'devicetypes' => '',
            'api-keys' => '',

            'feedback' => '',
        ]
    ];

    protected $storePermissions = [
        'store-config' => [
            '' => ''
        ],
        'employees' => [
            '' => '',
            'permissions' => '',
            'roles' => ''
        ]
    ];

    /**
     * @var int
     */
    protected $devStoreId;

    public function run()
    {
        $this->devStoreId = \Biffy\Entities\Store\Store::where('name', '=', 'Dev')->first()->id;
        $this->seedData($this->globalPermissions, true);
        $this->seedData($this->storePermissions, false);
    }

    private function seedData($data, $global)
    {
        foreach ($data as $groupKey => $permissionGroup) {
            foreach ($permissionGroup as $permissionKey => $permissionDescription) {
                if ($groupKey === '') {
                    $name = $permissionKey;
                } else if ($permissionKey === '') {
                    $name = $groupKey;
                } else {
                    $name = $groupKey . '.' . $permissionKey;
                }
                $this->create($name, $permissionDescription, $global);
            }
        }
    }

    private function create($name, $description, $global)
    {
        return Permission::create(
            [
                'name' => $name,
                'description' => $description,
                'global' => $global
            ]
        );
    }
}