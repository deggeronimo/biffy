<?php namespace Biffy\Services\Entities\Setting;

use Biffy\Entities\Setting\SettingRepositoryInterface;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\User\UserService;
use Biffy\Services\Entities\UserSetting\UserSettingService;

class SettingService extends Service
{
    public function __construct(SettingRepositoryInterface $settingRepositoryInterface)
    {
        $this->repo = $settingRepositoryInterface;
    }

    public function create($attributes)
    {
        $setting = parent::create($attributes);
        $this->handleNew($setting->id, $setting->default);
        return $setting;
    }

    public function getId($key)
    {
        $settings = $this->all()->toArray();

        return array_reduce($settings, function ($carry, $item) use ($key) {
                if (is_null($carry) && $item['key'] === $key) {
                    return $item['id'];
                }

                return $carry;
            });
    }

    public function all($ignoreCache = false)
    {
        $closure = function () use ($ignoreCache) {
            $settings = $this->repo->all();
            \Cache::put('settings', $settings);
            return $settings;
        };

        if (!$ignoreCache) {
            return \Cache::get('settings', $closure);
        }

        return $closure();
    }

    public function handleNew($id, $default)
    {
        $this->needsCache();
        /** @var UserService $storeService */
        $userService = app('Biffy\Services\Entities\User\UserService');
        $userIds = $userService->getAllIds();

        $data = array_map(function ($val) use ($id, $default) {
                return ['setting_id' => $id, 'user_id' => $val, 'value' => $default];
            }, $userIds);

        /** @var UserSettingService $userSettingService */
        $userSettingService = app('Biffy\Services\Entities\UserSetting\UserSettingService');
        $userSettingService->handleNew($data);
    }

    public function needsCache()
    {
        $this->all(true);
    }
} 