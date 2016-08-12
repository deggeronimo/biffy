<?php namespace Biffy\Services\Auth;

use Illuminate\Auth\Guard;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Session\Store as SessionStore;

class GoogleAuthGuard extends Guard
{
    public function __construct(Application $app, GoogleUserProvider $provider, SessionStore $session, Request $request = null)
    {
        $this->app = $app;
        $this->provider = $provider;
        $this->session = $session;
        $this->request = $request;
    }

    public function check()
    {
        return ! is_null($this->user());
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        if (\App::environment('testing')) {
            $user = new UbifUser(['email' => 'test.test@ubreakifix.com', 'given_name' => '', 'family_name' => '', 'store_id' => 1, 'id' => 1, 'admin' => 1, 'picture' => ''], true);
        } else if (php_sapi_name() === 'cli') {
            $user = new UbifUser(['email' => '', 'given_name' => '', 'family_name' => '', 'store_id' => '', 'id' => '', 'admin' => '', 'picture' => ''], true);
        } else if ($this->bypassAuth()) {
            $user = new UbifUser(['email' => 'test.test@ubreakifix.com', 'picture' => 'https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg']);
        } else {
            $user = $this->provider->retrieveByCredentials([]);
        }

        return $this->user = $user;
    }

    public function logout()
    {
        $this->session->forget($this->provider->getTokenName());
        $this->session->forget('user');
        $this->app->make('google-client')->revokeToken();
    }

    public function pinLogin($pin)
    {
        $pinUser = $this->getPinUser($pin);

        if ($pinUser) {
            $this->user()->setPinUser($pinUser);
            return true;
        }

        return false;
    }

    /**
     * @param $pin
     * @return bool|mixed
     */
    public function getPinUser($pin)
    {
        /** @var \Biffy\Services\Entities\User\UserService $userService */
        $userService = app('Biffy\Services\Entities\User\UserService');
        $user = $userService->getByPin($pin);
        $storeId = $this->user()->storeId();

        if (is_null($user)) {
            return false;
        }

        if (in_array($storeId, $user->storeIds())) {
            return $user;
        }

        return false;
    }

    public function pinLogout()
    {
        $this->session->forget('user.pin_user');
    }

    public function getAuthUrl()
    {
        return $this->provider->getAuthUrl();
    }

    public function authenticate($code)
    {
        return $this->provider->authenticateCode($code);
    }

    public function isAuthenticated()
    {
        if ($this->bypassAuth()) {
            return true;
        }

        return $this->provider->isAuthenticated();
    }

    public function hasPermission($name)
    {
        return $this->user()->hasPermission($name);
    }

    private function bypassAuth()
    {
        return in_array($this->app->environment(), ['local', 'staging']) && env('fake_user', false);
    }
} 
