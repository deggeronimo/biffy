<?php namespace Biffy\Services\Entities\UserSetting;

use Biffy\Entities\UserSetting\UserSettingRepositoryInterface;
use Biffy\Services\Entities\Service;

class UserSettingService extends Service
{
    public function __construct(UserSettingRepositoryInterface $userSettingRepositoryInterface)
    {
        $this->repo = $userSettingRepositoryInterface;
    }

    public function userSettings($userId)
    {
        return $this->repo->findAllBy('user_id', '=', $userId);
    }

    public function handleNew($data)
    {
        foreach ($data as $d) {
            $this->create($d);
            $this->needsCache($d['user_id']);
        }
    }

    public function needsCache($userId)
    {

    }
} 