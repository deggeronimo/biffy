<?php namespace Biffy\Providers;

class AuthServiceProvider extends \Illuminate\Auth\AuthServiceProvider
{
    public function register()
    {
        parent::register();

        $this->registerGoogleClient();
        $this->registerDriver();
    }

    protected function registerDriver()
    {
        $this->app['auth']->extend('google', function ($app) {
                /** @var \Illuminate\Foundation\Application $app */
                return $app->make('Biffy\Services\Auth\GoogleAuthGuard');
            }
        );
    }

    protected function registerGoogleClient()
    {
        $this->app['google-client'] = $this->app->share(
            function ($app) {
                $config = $app['config'];

                $client = new \Google_Client();
                $client->setClientId($config->get('gauth.clientId'));
                $client->setClientSecret($config->get('gauth.clientSecret'));
                $client->setRedirectUri($config->get('gauth.redirectUri'));
                $client->setScopes($config->get('gauth.scopes'));
                $client->setAccessType($config->get('gauth.accessType'));
                $client->setApprovalPrompt($config->get('gauth.approvalPrompt'));

                return $client;
            }
        );
    }
} 