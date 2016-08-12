<?php

class ApiCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('X-Codecept', 'asdf'); // todo specify value elsewhere
        $I->haveHttpHeader('X-Codecept-Opts', 'APP_ENV=testing;DB_CONNECTION_NAME=testing;CACHE_DRIVER=array;SESSION_DRIVER=array');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('X-Language-Code', 'en');
    }

    public function _after(ApiTester $I)
    {

    }
} 
