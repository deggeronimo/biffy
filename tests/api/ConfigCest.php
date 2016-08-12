<?php

require_once 'ApiCest.php';

// todo test cache
// todo test store config
class ConfigCest extends ApiCest
{
    public function _before(ApiTester $I)
    {
        parent::_before($I);
        $I->setUpStore();
    }

    public function initialState(ApiTester $I)
    {
        $this->emptyIndex($I);
    }

    public function create(ApiTester $I)
    {
        $I->sendPOST('config', $this->getExample());
        $I->seeResponseCodeIs(201);
    }
    
    public function show(ApiTester $I)
    {
        $I->sendGET('config/1');
        $I->seeResponseContainsJson($this->getExample());
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
    }

    public function index(ApiTester $I)
    {
        $I->sendGET('config');
        $I->seeResponseContainsJson($this->getExample());
        $I->seeResponseJsonMatchesJsonPath('$.data[0].id');
    }

    public function update(ApiTester $I)
    {
        $example = $this->getExample();
        $example['key'] = 'test-test';
        $I->sendPUT('config/1', $example);
        $I->seeResponseCodeIs(205);

        $I->sendGET('config/1');
        $I->seeResponseContainsJson($example);
    }

    public function destroy(ApiTester $I)
    {
        $I->sendDELETE('config/1');
        $I->seeResponseCodeIs(204);
    }

    public function finalState(ApiTester $I)
    {
        $this->emptyIndex($I);
    }

    protected function emptyIndex(ApiTester $I)
    {
        $I->sendGET('config');
        $I->seeResponseJsonMatchesJsonPath('$.data');
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[0]');
    }

    protected function getExample()
    {
        return [
            'name' => 'test',
            'key' => 'test',
            'type' => 'input',
            'extra' => '',
            'default' => 'default_value'
        ];
    }
}