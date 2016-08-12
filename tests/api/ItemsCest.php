<?php

require_once 'ApiCest.php';

// todo additional tests
class ItemsCest extends ApiCest
{
    public function _before(ApiTester $I)
    {
        parent::_before($I);
        $I->setUpStore();
        $I->setUpLanguage();
    }

    public function create(ApiTester $I)
    {
        $I->sendPOST('items', ['device_type_id' => null, 'item_number' => '12000', 'name' => 'Diagnostic']);
        $I->seeResponseCodeIs(201);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseContainsJson(['item_number' => '12000', 'name' => 'Diagnostic']);
    }

    public function verifyStoreItemCreated(ApiTester $I)
    {
        $I->sendGET('storeitems');
        $I->seeResponseJsonMatchesJsonPath('$.data[0].id');
        $I->seeResponseJsonMatchesJsonPath('$.data[0].item.id');
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[1]');
        $I->seeResponseContainsJson(['device_type_id' => null, 'id' => '1', 'stock' => '0']);
    }
}