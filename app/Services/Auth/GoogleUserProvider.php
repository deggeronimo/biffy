<?php namespace Biffy\Services\Auth;

use Biffy\Exceptions\InvalidAuthEmailException;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Application;
use Illuminate\Session\Store as SessionStore;

class GoogleUserProvider implements UserProvider
{
    /**
     * @var \Google_Client
     */
    protected $client;

    public function __construct(Application $app, SessionStore $session)
    {
        $this->app = $app;
        $this->session = $session;
        $this->client = $this->app->make('google-client');
        $this->oauth2 = new \Google_Service_Oauth2($this->client);

        if ($this->session->has($this->getTokenName())) {
            $this->client->setAccessToken($this->sessionGetAccessToken());
        }
    }

    /**
     * @param mixed $identifier
     * @return UserContract|null
     */
    public function retrieveById($identifier)
    {
        $user = $this->retrieveByCredentials([]);

        if ($user && $user->getAuthIdentifier() == $identifier) {
            return $user;
        }

        return null;
    }

    /**
     * @param array $credentials
     * @throws InvalidAuthEmailException
     * @return UserContract|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if ($this->session->has('user')) {
            $data = $this->session->get('user');
            $data['store_id'] = $this->session->get('user.store_id');
            $user = new UbifUser($data);
            return $user;
        }

        if ($this->client->getAccessToken()) {
            if ($this->client->isAccessTokenExpired()) {
                $refreshToken = $this->sessionGetRefreshToken();
                if (is_null($refreshToken)) {
                    return null;
                }

                $this->client->refreshToken($refreshToken);

                if ($this->client->isAccessTokenExpired()) {
                    return null;
                }
            }
            $userinfo = $this->oauth2->userinfo->get();

            $emailDomain = substr($userinfo->email, strpos($userinfo->email, '@') + 1);

            if ($emailDomain != 'ubreakifix.com') {
                throw new InvalidAuthEmailException;
            }

            $data = [];
            foreach ($userinfo as $k => $v) {
                $data[$k] = $v;
            }

            $data['store_id'] = $this->session->get('user.store_id');
            $this->session->set('user', $data);
            $user = new UbifUser($data);

            return $user;
        }

        return null;
    }

    public function isAuthenticated()
    {
        return $this->client->getAccessToken() && !$this->client->isAccessTokenExpired();
    }

    /**
     * @param UserContract $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        return false;
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function getTokenName($extra = '')
    {
        return 'googleauth_' . md5(get_class($this)) . $extra;
    }

    public function authenticateCode($code)
    {
        try {
            $token = $this->client->authenticate($code);
            $this->sessionSetAccessToken();
            return $token;
        } catch (\Google_Auth_Exception $e) {
            // todo error handling
        }
    }

    public function sessionSetAccessToken()
    {
        $this->session->put($this->getTokenName(), $this->client->getAccessToken());
    }

    public function sessionGetAccessToken()
    {
        return $this->session->get($this->getTokenName());
    }

    public function sessionGetRefreshToken()
    {
        $token = json_decode($this->session->get($this->getTokenName()), true);
        return array_key_exists('refresh_token', $token) ? $token['refresh_token'] : null;
    }

    public function retrieveByToken($identifier, $token) { return null; }
    public function updateRememberToken(UserContract $user, $token) {}
}