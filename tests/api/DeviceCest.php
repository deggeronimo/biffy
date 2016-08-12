<?php

require_once 'ApiCest.php';

class DeviceCest extends ApiCest
{
    public function _before(ApiTester $I)
    {
        parent::_before($I);
        $I->setUpLanguage();
    }

    public function create(ApiTester $I)
    {
        $device = $this->getExample();
        $I->sendPOST('devices', $device);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson($device);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
    }


    public function show(ApiTester $I)
    {
        $I->sendGET('devices/1');
        $I->seeResponseContainsJson($this->getExample());
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
    }

    public function update(ApiTester $I)
    {
        $I->sendPUT('devices/1', $this->getExample());
        $I->seeResponseCodeIs(205);
    }

    private function getExample()
    {
        return [
            'customer_id' => '1',
            'device_type_id' => '2',
            'name' => 'Test Device Name',
            'passcode' => '1234',
            'serial' => 'ABC123',
            'serial_type' => 'Hardware'
        ];
    }
}
