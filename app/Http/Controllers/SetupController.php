<?php

namespace Biffy\Http\Controllers;

use Biffy\Entities\Group\GroupRepositoryInterface;
use Biffy\Entities\User\UserRepositoryInterface;
use Biffy\Entities\Store\StoreRepositoryInterface;
use Biffy\Entities\Item\ItemRepositoryInterface;
use Biffy\Services\Directory\DirectoryService;
use Illuminate\Support\Facades\DB;
use Biffy\Facades\Access;
use Biffy\Facades\Timer;

class SetupController extends BaseController
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepo;

    /**
     * @var \Biffy\Entities\User\UserRepositoryInterface
     */
    protected $userRepo;

    /**
     * @var \Biffy\Entities\Store\StoreRepositoryInterface
     */
    protected $storeRepo;

    /**
     * @var ItemRepositoryInterface
     */
    protected $itemRepo;

    /**
     * @var DirectoryService
     */
    protected $directory;

    public function __construct(
        GroupRepositoryInterface $groupRepo,
        UserRepositoryInterface $userRepo,
        StoreRepositoryInterface $storeRepo,
        ItemRepositoryInterface $itemRepo,
        DirectoryService $directory
    ) {
        $this->groupRepo = $groupRepo;
        $this->userRepo = $userRepo;
        $this->storeRepo = $storeRepo;
        $this->itemRepo = $itemRepo;
        $this->directory = $directory;
    }

    public function postStore()
    {
        Access::check('ubif.setup.store');

        $storeName = $this->input('name');
        $groupName = 'Group ' . $storeName; // Group Store Name
        $groupEmail = 'group_' . str_replace(' ', '', strtolower($storeName)) . '@ubreakifix.com'; // group_storename@ubreakifix.com
        $storeEmail = str_replace('group_', '', $groupEmail); // storename@ubreakifix.com
        $storePassword = 'ubreakifix_' . str_replace('@ubreakifix.com', '', $storeEmail); // ubreakfix_storename
        $storeGivenName = 'uBreakiFix';

        // create store user
        $this->directory->createUser($storeGivenName, $storeName, $storePassword, $storeEmail);
        $userId = $this->userRepo->create(['given_name' => $storeGivenName, 'family_name' => $storeName, 'email' => $storeEmail])->id;

        // create store group
        $groupId = $this->groupRepo->create(['name' => $groupName, 'email' => $groupEmail, 'is_store' => true])->id;
        $this->directory->createGroup($groupEmail, $groupName);

        // add store user to store group
        $this->groupRepo->addUser($groupId, $userId);

        // create store
        $storeId = $this->storeRepo->create(['name' => $storeName, 'group_id' => $groupId])->id;

        // add items to store
        $this->setupItems($storeId);
    }

    private function setupItems($storeId)
    {
        $itemIds = $this->itemRepo->getGlobalIds();

        $this->storeRepo->assignItems($storeId, $itemIds);
    }

    /**
     * Imports groups and users from Google via Directory API
     */
    public function getImport()
    {
//        Access::check('ubif.setup.import');

        Timer::start('import');
        $this->prepareForImport();

        Timer::start('import.groups');
        $groupData = $this->importGroups();
        Timer::stop('import.groups');

        Timer::start('import.users');
        $userData = $this->importUsers();
        Timer::stop('import.users');

        Timer::start('import.members');
        $this->importGroupMembers($groupData, $userData);
        Timer::stop('import.members');

        Timer::stop('import');

        dd(Timer::dump());

        return 'Import process complete.';
    }

    private function prepareForImport()
    {
        set_time_limit(1000000);

        DB::table('groups')->truncate();
        DB::table('users')->truncate();
        DB::table('group_user')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('stores')->truncate();
    }

    private function importGroups()
    {
        $groupData = [];
        $nextPageToken = null;

        do {
            // create groups
            Timer::checkpoint('import.groups', 'before.listGroups');
            $groupResult = $this->directory->listGroups('ubreakifix.com', $nextPageToken);
            $nextPageToken = $groupResult->getNextPageToken();
            $groups = $groupResult->getGroups();
            Timer::checkpoint('import.groups', 'after.listGroups');

            $groupData += $this->populateGroupData($groups);
        } while (!is_null($nextPageToken));

        return $groupData;
    }

    /**
     * @param $groups
     * @return mixed
     */
    private function populateGroupData($groups)
    {
        $groupData = [];
        foreach ($groups as $group) {
            Timer::checkpoint('import.groups', 'start.groupData');
            // insert group
            // is_store = 1 if group_*@ubreakifix.com
            $isStore = stripos($group->email, 'group_') !== false ? true : false;
            $groupId = $this->groupRepo->create(['name' => $group->name, 'email' => $group->email, 'is_store' => $isStore])->id;
            $groupData[$group->email]['id'] = $groupId;
            $groupData[$group->email]['members'] = [];

            if ($isStore) {
                // create store
                Timer::checkpoint('import.groups', 'before.setupItems');
                $storeId = $this->storeRepo->create(['name' => str_replace('Group ', '', $group->name), 'group_id' => $groupId])->id;
                $this->setupItems($storeId);
                Timer::checkpoint('import.groups', 'after.setupItems');
            }

            // get group members
            $nextPageToken = null;
            $groupMembers = [];

            do {
                Timer::checkpoint('import.groups', 'before.listGroupMembers');
                $groupMemberResult = $this->directory->listGroupMembers($group->email, $nextPageToken);
                $nextPageToken = $groupMemberResult->getNextPageToken();
                $groupMemberObject = $groupMemberResult->toSimpleObject();
                Timer::checkpoint('import.groups', 'after.listGroupMembers');
                if (isset($groupMemberObject->members)) {
                    $groupMembers += $groupMemberObject->members;
                }
            } while (!is_null($nextPageToken));

            foreach ($groupMembers as $member) {
                if (array_key_exists('email', $member)) {
                    $groupData[$group->email]['members'][] = $member['email'];
                }
            }
            Timer::checkpoint('import.groups', 'end.groupData');
        }
        return $groupData;
    }

    private function importUsers()
    {
        $userData = [];
        $nextPageToken = null;

        do {
            // create users
            Timer::checkpoint('import.users', 'before.listUsers');
            $userResult = $this->directory->listUsers('ubreakifix.com', $nextPageToken);
            $nextPageToken = $userResult->getNextPageToken();
            $users = $userResult->getUsers();
            Timer::checkpoint('import.users', 'after.listUsers');

            $userData += $this->populateUserData($users);
        } while (!is_null($nextPageToken));

        return $userData;
    }

    /**
     * @param $users
     * @return array
     */
    private function populateUserData($users)
    {
        $userData = [];
        /** @var \Google_Service_Directory_User $user */
        foreach ($users as $user) {
            Timer::checkpoint('import.users', 'start.userData');
            $userData[$user->getPrimaryEmail()] = $this->userRepo->create([
                'given_name' => $user->getName()->givenName,
                'family_name' => $user->getName()->familyName,
                'email' => $user->getPrimaryEmail()
            ])->id;
            Timer::checkpoint('import.users', 'end.userData');
        }
        return $userData;
    }

    /**
     * @param $groupData
     * @param $userData
     */
    private function importGroupMembers($groupData, $userData)
    {
        // insert group <-> user data
        foreach ($groupData as $groupEmail => $group) {
            $groupMembers = [];
            foreach ($group['members'] as $member) {
                if (array_key_exists($member, $userData)) {
                    $groupMembers[] = $userData[$member];
                }
            }

            if (count($groupMembers) > 0) {
                $this->groupRepo->addUser($group['id'], $groupMembers);
            }
        }
    }
} 