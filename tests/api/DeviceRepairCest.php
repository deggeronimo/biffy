<?php

require_once 'ApiCest.php';

class DeviceRepairCest extends ApiCest
{
    protected $deviceRepairId;
    protected $deviceRepairTypeId;
    protected $deviceTypeId;

    public function _before(ApiTester $I)
    {
        parent::_before($I);
        $I->setUpLanguage();
    }

    public function _after(ApiTester $I)
    {
    }

    public function createDeviceRepairType(ApiTester $I)
    {
        $I->sendPOST('devicerepairtypes', [
            'image_overlay' => '',
            'class' => '',
            'sort_order' => 0
        ]);
        $I->seeResponseCodeIs(201);

        $this->deviceRepairTypeId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $deviceRepairTypeNameLanguageKey = "device_repair_type_{$this->deviceRepairTypeId}_name";

        $I->setLanguageStrings($deviceRepairTypeNameLanguageKey, 'Glass Repair');

        $I->sendGET("devicerepairtypes/{$this->deviceRepairTypeId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id' => $this->deviceRepairTypeId,
        ]);
    }

    public function createDeviceType(ApiTester $I)
    {
        $name = 'Test Device Repair';

        $I->sendPOST('devicetypes', []);
        $I->seeResponseCodeIs(201);

        $this->deviceTypeId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $deviceTypeNameLanguageKey = "device_type_{$this->deviceTypeId}_name";

        $I->setLanguageStrings($deviceTypeNameLanguageKey, $name);

        $I->sendGET("devicetypes/{$this->deviceTypeId}");
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseContainsJson([
            'id' => $this->deviceTypeId,
            'name' => $name
        ]);
    }

    public function createRepair(ApiTester $I)
    {
        //TODO: Create DeviceType first
        $I->sendPOST('devicerepairs', [
            'device_repair_type_id' => 1,
            'device_type_id' => $this->deviceTypeId
        ]);
        $I->seeResponseCodeIs(201);

        $this->deviceRepairId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $deviceRepairNameLanguageKey = "device_repair_{$this->deviceRepairId}_name";

        $I->sendGET("languagekeys/{$deviceRepairNameLanguageKey}/strings");
        $I->seeResponseContainsJson([
            'string' => 'Test Device Glass Repair'
        ]);
    }
}