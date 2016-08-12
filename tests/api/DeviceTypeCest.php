<?php

require_once 'ApiCest.php';

// todo test more levels
// todo verify presence of attributes used in front-end
class DeviceTypeCest extends ApiCest
{
    protected $familyId;
    protected $familyName = 'iPhone';

    protected $manufacturerId;
    protected $manufacturerName = 'Apple';

    protected $childDeviceTypeId;
    protected $childDeviceTypeName = 'iPhone 6';

    protected $rootDeviceTypeId;
    protected $rootDeviceTypeName = 'Root Device Type';

    public function _before(ApiTester $I)
    {
        parent::_before($I);
        $I->setUpLanguage();
    }

    public function createRootDeviceType(ApiTester $I)
    {
        $I->sendPOST('devicetypes', [
            'top' => 1
        ]);
        $I->seeResponseCodeIs(201);

        $this->rootDeviceTypeId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $rootDeviceTypeNameLanguageKey = "device_type_{$this->rootDeviceTypeId}_name";

        $I->setLanguageStrings($rootDeviceTypeNameLanguageKey, $this->rootDeviceTypeName);

        $I->sendGET("devicetypes/{$this->rootDeviceTypeId}");
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseContainsJson([
            'id' => $this->rootDeviceTypeId,
            'parent_device_type_id' => null,
            'top' => '1'
        ]);
    }

    public function createDeviceFamily(ApiTester $I)
    {
        $I->sendPOST('devicefamilies', ['name' => $this->familyName]);
        $I->seeResponseCodeIs(201);

        $this->familyId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
    }

    public function createDeviceManufacturer(ApiTester $I)
    {
        $I->sendPOST('devicemanufacturers', ['name' => $this->manufacturerName]);
        $I->seeResponseCodeIs(201);

        $this->manufacturerId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
    }

    public function createChildDeviceType(ApiTester $I)
    {
        $I->sendPOST('devicetypes', [
            'parent_device_type_id' => $this->rootDeviceTypeId,
            'top' => 0,
            'device_manufacturer_id' => $this->manufacturerId,
            'device_family_id' => $this->familyId
        ]);
        $I->seeResponseCodeIs(201);

        $this->childDeviceTypeId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $childDeviceTypeNameLanguageKey = "device_type_{$this->childDeviceTypeId}_name";

        $I->setLanguageStrings($childDeviceTypeNameLanguageKey, $this->childDeviceTypeName);

        $I->sendGET("devicetypes/{$this->childDeviceTypeId}");
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseJsonMatchesJsonPath('$.data.parent_device_type.id');
        $I->seeResponseContainsJson([
                'id' => $this->childDeviceTypeId,
                'top' => '0',
                'pos_selectable' => true,
                'parent_device_type' => [
                    'pos_selectable' => false,
                    'id' => $this->rootDeviceTypeId
                ],
                'device_family' => [
                    'id' => $this->familyId
                ],
                'device_manufacturer' => [
                    'id' => $this->manufacturerId
                ]
            ]
        );
    }

    public function filterDeviceTypes(ApiTester $I)
    {
        $I->sendGET('devicetypes?all=1&filter[top]=1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id' => $this->rootDeviceTypeId
        ]);
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[1]');

        $I->sendGET("devicetypes?all=1&filter[parent_id]={$this->rootDeviceTypeId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id' => $this->childDeviceTypeId
        ]);
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[1]');

        $I->sendGET('devicetypes?all=1&filter[name]=iphone');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id' => $this->childDeviceTypeId
        ]);
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[1]');
    }
}
