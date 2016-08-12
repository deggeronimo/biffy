<?php

require_once 'ApiCest.php';

// todo test update, delete, additional actions
class CustomerCest extends ApiCest
{
    public function _before(ApiTester $I)
    {
        parent::_before($I);
        $I->setUpStore();
    }

    public function create(ApiTester $I)
    {
        $I->sendPOST('customers', $this->getExample());
        $I->seeResponseCodeIs(201);
    }

    public function search(ApiTester $I)
    {
        $I->sendGET('customers?all=1&filter[search]=test+person');
        $I->seeResponseJsonMatchesJsonPath('$.data[0].id');
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[1]');

        $I->sendGET('customers?all=1&filter[search]=34567');
        $I->seeResponseJsonMatchesJsonPath('$.data[0].id');
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[1]');

        $I->sendGET('customers?all=1&filter[search]=abc');
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[0]');
    }

    public function show(ApiTester $I)
    {
        $I->sendGET('customers/1');
        $I->seeResponseContainsJson($this->getExample());
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
    }

    protected function getExample()
    {
        return [
            'given_name' => 'Test',
            'family_name' => 'Person',
            'phone' => '1234567890',
            'email' => 'test@ubreakifix.com',
            'address_line_1' => '123 Test St',
            'address_line_2' => 'Ste 100',
            'city' => 'Test',
            'state' => 'TE',
            'postal_code' => '12345',
            'country' => 'US',
            'referral_source' => 'Friend',
            'store_id' => '1'
        ];
    }
}