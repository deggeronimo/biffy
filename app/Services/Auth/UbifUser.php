<?php namespace Biffy\Services\Auth;

use Illuminate\Auth\GenericUser;

class UbifUser extends GenericUser
{
    /**
     * @var \Biffy\Services\Entities\User\UserService
     */
    private $userService;

    public function __construct($attributes, $skip = false)
    {
        $this->userService = app('Biffy\Services\Entities\User\UserService');
        if (!$skip) {
            $user = $this->userService->findOrCreate($attributes);

            if ($user->picture_url != $attributes['picture']) {
                $this->userService->updatePictureUrl($user->id, $attributes['picture']);
                $user->picture_url = $attributes['picture'];
            }

            $attributes['admin'] = $user->admin;

            $data = array_merge($attributes, $user->toArray());

            $data['needs_pin'] = false;
            $username = str_replace('@ubreakifix.com', '', $data['email']);

            if (strpos($username, '.') === false) {
                $data['needs_pin'] = true;
            }

            if (!array_key_exists('store_id', $data) || is_null($data['store_id'])) {
                $data['store_id'] = $this->userService->getDefaultGroup($user); // todo handle user with no store groups
                \Session::put('user.store_id', $data['store_id']);
            }

            if (\Session::has('user.pin_user')) {
                $data['pinUser'] = \Session::get('user.pin_user');
            }
        } else {
            $data = $attributes;
        }

        parent::__construct($data);
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function language()
    {
        return 'en';
    }

    public function currentUser($usePinUser = true)
    {
        if ($usePinUser && isset($this->pinUser)) {
            return $this->pinUser;
        }

        return $this;
    }

    public function userId($usePinUser = true)
    {
        return $this->currentUser($usePinUser)->id;
    }

    public function storeId()
    {
        return $this->store_id;
    }

    public function isAdmin()
    {
        //TODO: return true or false?
        return $this->currentUser()->admin; // == '1';
    }

    public function setStoreId($id)
    {
        $this->store_id = $id;
        \Session::put('user.store_id', $id);
    }

    public function setPinUser($user)
    {
        // todo add user settings to pinUser
        $user->permissions = $this->userService->getUserPermissions($user, $this->storeId());
        $this->pinUser = $user;
        \Session::put('user.pin_user', $user);
    }

    public function hasPermission($name)
    {
        $permissions = $this->currentUser()->permissions;
        return array_key_exists($name, $permissions) && $permissions[$name];
    }
} 