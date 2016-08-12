<?php

require_once 'ApiCest.php';

// todo test cache
// todo test user settings
class SettingsCest extends ApiCest
{
    public function initialState(ApiTester $I)
    {
        $this->emptyIndex($I);
    }

    public function create(ApiTester $I)
    {
        $I->sendPOST('settings', $this->getExample());
        $I->seeResponseCodeIs(201);
    }

    public function show(ApiTester $I)
    {
        $I->sendGET('settings/1');
        $I->seeResponseContainsJson($this->getExample());
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
    }

    public function index(ApiTester $I)
    {
        $I->sendGET('settings');
        $I->seeResponseContainsJson($this->getExample());
        $I->seeResponseJsonMatchesJsonPath('$.data[0].id');
    }

    public function update(ApiTester $I)
    {
        $example = $this->getExample();
        $example['key'] = 'test-test';
        $I->sendPUT('settings/1', $example);
        $I->seeResponseCodeIs(205);

        $I->sendGET('settings/1');
        $I->seeResponseContainsJson($example);
    }

    public function destroy(ApiTester $I)
    {
        $I->sendDELETE('settings/1');
        $I->seeResponseCodeIs(204);
    }

    public function finalState(ApiTester $I)
    {
        $this->emptyIndex($I);
    }

    protected function emptyIndex(ApiTester $I)
    {
        $I->sendGET('settings');
        $I->seeResponseJsonMatchesJsonPath('$.data');
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[0]');
    }

    protected function getExample()
    {
        return [
            'name' => 'Test',
            'key' => 'test',
            'type' => 'input',
            'extra' => '',
            'default' => 'default_value'
        ];
    }
}