<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\User\UserService;

class AuthController extends ApiController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getMe()
    {
        $user = \Auth::user();

        if (is_null($user)) {
            return $this->statusUnauthorized()->respond();
        }

        /** @var \Biffy\Services\Entities\Store\StoreService $storeService */
        $storeService = app('Biffy\Services\Entities\Store\StoreService');
        $user->currentStore = $storeService->getWithConfig($user->store_id);

        $storeId = $user->currentStore['id'];

        $user->permissions = $this->filterPermissions($user->permissions, $storeId);

        $morph = [
            'id', 'name', 'picture_url', 'store_id', 'admin', 'given_name', 'family_name', 'username', 'rep', 'needs_pin',
            'permissions' => ['[]' => []],
            'currentStore' => ['id', 'name', 'config' => ['[]' => ['id', 'key', 'config_id', 'value']]],
            'stores' => ['[]' => ['id', 'name']],
            'settings' => ['[]' => ['id', 'setting_id', 'value']]
        ];

        $data = $user->toArray();
        $data = $this->morph($data, $morph);
        return $this->data($data)->respond();
    }

    private function filterPermissions($permissions, $storeId)
    {
        foreach ($permissions as $permissionKey => $val) {
            if (is_array($val)) {
                $permissions[$permissionKey] = $val[$storeId];
            }
        }

        return $permissions;
    }

    public function deleteMe()
    {
        \Auth::logout();
        \Auth::pinLogout();
    }

    public function postIndex()
    {
        $token = \Auth::authenticate($this->input('code'));
        return ['token' => $token, 'user' => \Auth::user()];
    }

    public function postStore()
    {
        $user = $this->userService->find(\Auth::user()->userId(false));
        $storeId = $this->input('store_id');

        if ($user->admin || in_array($storeId, $user->storeIds())) {
            \Auth::user()->setStoreId($storeId);
            return $this->getMe();
        } else {
            return $this->statusForbidden()->respond();
        }
    }

    public function postPin()
    {
        $pin = $this->input('pin');

        if (\Auth::pinLogin($pin)) {
            $pinUser = \Auth::user()->currentUser();
            $pinUser->permissions = $this->filterPermissions($pinUser['permissions'], \Auth::user()->storeId());
            $arr = $pinUser->toArray();
            $arr['admin'] = $pinUser->admin;
            return $this->data($arr)->respond();
        }

        return $this->statusForbidden()->respond();
    }

    public function deletePin()
    {
        \Auth::pinLogout();
    }

    public function postPinPermissions()
    {
        $pin = $this->input('pin');
        $pinUser = \Auth::getPinUser($pin);
        if (!$pinUser) {
            return $this->statusUnauthorized()->respond();
        }

        $pinUser->permissions = $this->userService->getUserPermissions($pinUser, \Auth::user()->storeId());
        return $this->data($pinUser)->respond();
    }
}