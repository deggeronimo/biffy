<?php namespace Biffy\Services\Entities\User;

use Biffy\Entities\User\UserRepositoryInterface;
use Biffy\Services\Entities\Group\GroupService;
use Biffy\Services\Entities\Service;

/**
 * Class UserService
 * @package Biffy\Services\Entities\User
 */
class UserService extends Service
{
    /**
     * @param UserRepositoryInterface $repo
     */
    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAllIds()
    {
        return $this->repo->getAllIds();
    }

    public function updatePictureUrl($userId, $pictureUrl)
    {
        $this->repo->update($userId, ['picture_url' => $pictureUrl]);
    }

    /**
     * @param $id
     * @param $setGroups
     * @return mixed
     */
    public function setGroups($id, $setGroups)
    {
        \Cache::userId($id)->forget('store-list');
        return $this->repo->setGroups($id, $setGroups);
    }

    public function getAdminUsers()
    {
        return $this->repo->getAdminUsers()->toArray();
    }

    public function getAdminIds()
    {
        return $this->repo->getAdminIds();
    }

    public function getUsers(array $userIds)
    {
        return $this->repo->getUsers($userIds);
    }

    public function usernameList($search)
    {
        return $this->repo->usernameList($search);
    }

    public function findOrCreate($userData)
    {
        $user = $this->repo->getUserByEmail($userData['email']);

        if (is_null($user)) {
            $this->createUser($userData);
        }

        $user->stores = $this->userStores($user->id, $user->admin);
        $user->permissions = $this->getUserPermissions($user);

        return $user;
    }

    public function getUserPermissions($user, $useStoreId = null)
    {
        $handledPermissions = [];
        if (!$user->admin) {
            $permissionList = app('Biffy\Services\Entities\Permission\PermissionService')->all();

            if (\Cache::userId($user->id)->has('permissions')) {
                $userPermissions = \Cache::userId($user->id)->get('permissions');
            } else {
                /** @var \Biffy\Entities\User\User $user */
                $permissions = $user->permissions()->get();
                $permissionsCache = $permissions->toArray();
                $userPermissions = array_map(
                    function ($val) {
                        return [
                            'id' => $val['id'],
                            'name' => $val['name'],
                            'store_id' => $val['pivot']['store_id']
                        ];
                    },
                    $permissionsCache
                );
                \Cache::userId($user->id)->put('permissions', $userPermissions);
            }

            $mappedUserPermissions = [];
            foreach ($userPermissions as $userPermission) {
                if (is_null($userPermission['store_id'])) {
                    $mappedUserPermissions[$userPermission['name']] = true;
                } else {
                    $mappedUserPermissions[$userPermission['name']][$userPermission['store_id']] = true;
                }
            }

            foreach ($permissionList as $permission) {
                if ($permission['global']) {
                    $handledPermissions[$permission['name']] = array_key_exists($permission['name'], $mappedUserPermissions);
                } else {
                    $closure = function ($permission, $mappedUserPermissions, $storeId) {
                        return array_key_exists(
                            $permission['name'],
                            $mappedUserPermissions
                        ) ? array_key_exists($storeId, $mappedUserPermissions[$permission['name']]) : false;
                    };

                    if (is_null($useStoreId)) {
                        foreach ($user->stores as $store) {
                            $storeId = $store['id'];
                            $handledPermissions[$permission['name']][$storeId] = $closure($permission, $mappedUserPermissions, $storeId);
                        }
                    } else {
                        $handledPermissions[$permission['name']][$useStoreId] = $closure($permission, $mappedUserPermissions, $useStoreId);
                    }
                }
            }
        }

        return $handledPermissions;
    }

    public function createUser($userData)
    {
        $user = $this->repo->create([
                'given_name' => $userData['givenName'],
                'family_name' => $userData['familyName'],
                'email' => $userData['email'],
                'admin' => 0
            ]);

        // todo create user_settings records
        // todo create permission_user records
        // todo create user profile?

        /** @var \Biffy\Services\Directory\DirectoryService $directory */
        $directory = app('Biffy\Services\Directory\DirectoryService');
        $usersGroups = $directory->getUsersGroups($user->email)->getGroups();

        /** @var GroupService $groupService */
        $groupService = app('Biffy\Services\Entities\Group\GroupService');
        $groups = $groupService->all()->toArray();
        $groups = array_combine(array_map(function ($group){
                    return $group['email'];
                }, $groups), array_values($groups));

        $groupIds = [];
        foreach ($usersGroups as $userGroup) {
            if (array_key_exists($userGroup->getEmail(), $groups)) {
                $groupIds[] = $groups[$userGroup->getEmail()]['id'];
            }
        }

        $this->setGroups($user->id, $groupIds);

        return $this->repo->find($user->id);
    }

    public function createFromGoogle($user, $admins = [])
    {
        $this->create([
                'given_name' => $user->getName()->givenName,
                'family_name' => $user->getName()->familyName,
                'email' => $user->getPrimaryEmail(),
                'username' => str_replace('@ubreakifix.com', '', $user->getPrimaryEmail()),
                'admin' => in_array(str_replace('@ubreakifix.com', '', $user->getPrimaryEmail()), $admins),
                'pin' => null
            ]);
    }

    public function getDefaultGroup($user)
    {
        // get default store if selected
        $settingId = app('Biffy\Services\Entities\Setting\SettingService')->getId('default-store');
        $settings = $user->settings->toArray();

        $defaultStore = array_reduce($settings, function ($carry, $item) use ($settingId) {
                if (is_null($carry) && $item['setting_id'] === $settingId) {
                    return $item['value'];
                }

                return $carry;
            });

        if (!is_null($defaultStore)) {
            return $defaultStore;
        }

        // else, select first group of user
        $groups = $user->groups;
        foreach ($groups as $group)
        {
            if ($group->is_store)
            {
                return $group->store->id;
            }
        }

        return 0;
    }

    public function getWithGroups($id)
    {
        return $this->repo->with(['groups'])->find($id);
    }

    public function getByPin($pin)
    {
        return $this->repo->with(['settings'])->findFirstBy('pin', $pin);
    }

    public function userStores($userId, $admin = false)
    {
        // todo update admin-store-list when new store
        if ($admin) {
            return \Cache::get('admin-store-list', function () {
                    $stores = app('Biffy\Services\Entities\Store\StoreService')->all()->toArray();
                    \Cache::put('admin-store-list', $stores);
                    return $stores;
                });
        }

        return \Cache::userId($userId)->get('store-list', function () use ($userId) {
                $stores = $this->repo->find($userId)->stores()->toArray();
                \Cache::userId($userId)->put('store-list', $stores);
                return $stores;
            });
    }

    public function findByUsernames($usernames)
    {
        return $this->repo->findByUsernames($usernames);
    }

    public function getWithProfile($id, $getBoards = false)
    {
        if (\Cache::userId($id)->has('profile')) {
            if ($getBoards) {
                $user = $this->repo->getWithBoardActivity($id);
            } else {
                $user = $this->repo->find($id);
            }

            $user->profile = \Cache::userId($id)->get('profile');

            return $user;
        }

        if ($getBoards) {
            $user = $this->repo->getFullProfile($id);
        } else {
            $user = $this->repo->getWithProfile($id);
        }
        \Cache::userId($id)->put('profile', $user->profile);
        return $user;
    }

    public function updateProfile($id, $input)
    {
        /** @var \Biffy\Entities\UserProfile\UserProfileRepositoryInterface $profileRepo */
        $profileRepo = app('Biffy\Entities\UserProfile\UserProfileRepositoryInterface');
        /** @var \Biffy\Entities\UserProfile\UserProfile $profile */
        $profile = $profileRepo->findFirstBy('user_id', $id);
        if (is_null($profile)) {
            $input['user_id'] = $id;
            $profile = $profileRepo->create($input);
        } else {
            $profile->update($input);
        }

        \Cache::userId($id)->put('profile', $profile);
    }

    public function updateSettings($id, $input)
    {
        $user = $this->repo->find($id);

        $settings = [];
        foreach ($input as $setting) {
            $settings[$setting['id']] = $setting['value'];
        }

        $userSettings = $user->settings;
        foreach ($userSettings as $k => $setting) {
            $setting['value'] = $settings[$setting['id']];
            $user->settings()->save($setting);
        }
    }
}