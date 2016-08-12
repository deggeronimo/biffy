<?php

require_once 'ApiCest.php';

// todo workorder from existing device
class WorkorderCest extends ApiCest
{
    protected $customerId;

    protected $deviceId;

    protected $deviceTypeId;
    protected $deviceTypeName = 'iPhone 6';

    protected $itemList = [
        [ 'item_number' => '10000', 'name' => 'Diagnostic', 'unit_price' => '0', 'labor_cost' => '0' ],
        [ 'item_number' => '10001', 'name' => 'iPhone 6 Item', 'unit_price' => '99.99', 'labor_cost' => '79.99' ]
    ];

    protected $saleId;

    protected $saleItemList = [
        [ ],
        [ ]
    ];

    protected $salePaymentList = [];

    protected $storeId;

    protected $storeItemList = [
        [ ],
        [ ]
    ];

    protected $workorderId;

    protected $workorderNoteList = [
        [
            'id' => '1',
            'public' => '0',
            'notes' => 'There are parts that need to be ordered.<br/>This status was automatically generated.'
        ],
        [
            'id' => '2',
            'public' => '0',
            'notes' => 'added Diagnostic'
        ],
        [
            'id' => '3',
            'public' => '0',
            'notes' => 'added iPhone 6 Item'
        ],
        [
            'id' => '4',
            'public' => '0',
            'notes' => 'updated iPhone 6 Item price from 99.99 to 79.99'
        ]
    ];

    public function _before(ApiTester $I)
    {
        parent::_before($I);
        $I->setUpLanguage();
    }

    public function createStore(ApiTester $I)
    {
        $I->sendPOST('stores', [
            'name' => 'Dev',
            'group_id' => '1'
        ]);
        $I->seeResponseCodeIs(201);

        $this->storeId = strval($I->grabDataFromResponseByJsonPath('$.data.id')[0]);
    }

    public function createAndSearchCustomer(ApiTester $I)
    {
        $I->sendPOST('customers', [
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
            'store_id' => $this->storeId
        ]);
        $I->seeResponseCodeIs(201);

        $this->customerId = strval($I->grabDataFromResponseByJsonPath('$.data.id')[0]);

        $I->sendGET("customers/{$this->customerId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseContainsJson([
            'id' => $this->customerId
        ]);

        $I->sendGET('customers?all=1&filter[search]=test');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id' => $this->customerId
        ]);
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[1]');

        $I->sendGET('customers?all=1&filter[search]=asdf');
        $I->seeResponseCodeIs(200);
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[0]');
    }

    public function createDeviceType(ApiTester $I)
    {
        $I->sendPOST('devicetypes', []);
        $I->seeResponseCodeIs(201);

        $this->deviceTypeId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $deviceTypeNameLanguageKey = "device_type_{$this->deviceTypeId}_name";

        $I->setLanguageStrings($deviceTypeNameLanguageKey, $this->deviceTypeName);

        $I->sendGET("devicetypes/{$this->deviceTypeId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseContainsJson([
            'id' => $this->deviceTypeId,
            'parent_device_type_id' => null,
        ]);
    }

    public function createDevice(ApiTester $I)
    {
        $I->sendPOST('devices', [
            'customer_id' => $this->customerId,
            'device_type_id' => $this->deviceTypeId,
            'name' => 'Device Name',
            'passcode' => '1234',
            'serial' => 'ABC123',
            'serial_type' => 'Hardware'
        ]);
        $I->seeResponseCodeIs(201);

        $this->deviceId = strval($I->grabDataFromResponseByJsonPath('$.data.id')[0]);

        $I->sendGET("devices/{$this->deviceId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseContainsJson([
            'id' => $this->deviceId,
            'customer_id' => $this->customerId,
            'device_type_id' => $this->deviceTypeId
        ]);
    }

    public function getCustomerDevices(ApiTester $I)
    {
        $I->sendGET("devices?all=1&filter[customer_id]={$this->customerId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id' => $this->deviceId
        ]);
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[1]');

        $I->sendGET("devices?all=1&filter[customer_id]=5161613");
        $I->seeResponseCodeIs(200);
        $I->dontSeeResponseJsonMatchesJsonPath('$.data[0]');
    }

    public function createSale(ApiTester $I)
    {
        $I->sendPOST('sales', [
            'customer_id' => 1
        ]);
        $I->seeResponseCodeIs(201);

        $this->saleId = strval($I->grabDataFromResponseByJsonPath('$.data.id')[0]);

        $I->sendGET("sales/{$this->saleId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseContainsJson([
            'id' => $this->saleId,
            'customer_id' => $this->customerId,
            'store_id' => $this->storeId
        ]);
    }

    public function createWorkorder(ApiTester $I)
    {
        $nextUpdate = microtime(true);

        $I->sendPOST('workorders', [
            'device_id' => $this->deviceId,
            'sale_id' => $this->saleId,
            'next_update' => $nextUpdate,
            'quickdiag' => '[]',
            'itemswithdevice' => '[]',
            'rating' => '1',
            'notes' => '',
            'workorder_status_id' => '1'
        ]);
        $I->seeResponseCodeIs(201);

        $nextUpdate = date('Y-m-d H:i:s', $nextUpdate);

        $this->workorderId = strval($I->grabDataFromResponseByJsonPath('$.data.id')[0]);

        $I->sendGET("workorders/{$this->workorderId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseContainsJson([
            'id' => $this->workorderId,
            'device_id' => $this->deviceId,
            'sale_id' => $this->saleId,
            'next_update' => $nextUpdate
        ]);
    }

    public function createItems(ApiTester $I)
    {
        foreach ($this->itemList as & $item)
        {
            $newItem = [
                'item_number' => $item['item_number'],
                'name' => $item['name'],
                'global' => '1',
                'unit_price' => $item['unit_price'],
                'labor_cost' => $item['labor_cost']
            ];

            $I->sendPOST('items', $newItem);
            $I->seeResponseCodeIs(201);

            $item['id'] = strval($I->grabDataFromResponseByJsonPath('$.data.id')[0]);

            $I->sendGET("items/{$item['id']}");
            $I->seeResponseJsonMatchesJsonPath('$.data.id');
            $I->seeResponseContainsJson([
                'data' => array_merge($newItem, [ 'id' => $item['id'] ])
            ]);
        }
    }

    public function searchForStoreItem(ApiTester $I)
    {
        $checkList = [
            [
                'search' => 'diag',
                'count' => 1
            ],
            [
                'search' => 'iphone',
                'count' => 1
            ],
            [
                'search' => 'asdf',
                'count' => 0
            ]
        ];

        $i = 0;
        foreach ($checkList as $check)
        {
            $count = $check['count'];

            $I->sendGET("storeitems?filter[search]={$check['search']}");
            $I->seeResponseCodeIs(200);

            if ($count > 0)
            {
                $I->seeResponseJsonMatchesJsonPath('$.data[0].id');

                $I->seeResponseContainsJson([
                    'item' => [
                        'id' => $this->itemList[$i]['id'],
                        'name' => $this->itemList[$i]['name'],
                        'unit_price' => $this->itemList[$i]['unit_price'],
                        'labor_cost' => $this->itemList[$i]['labor_cost']
                    ]
                ]);

                $this->storeItemList[$i]['id'] = strval($I->grabDataFromResponseByJsonPath('$.data[0].id')[0]);
                $this->storeItemList[$i]['unit_price'] = strval($I->grabDataFromResponseByJsonPath('$.data[0].unit_price')[0]);
                $this->storeItemList[$i]['labor_cost'] = strval($I->grabDataFromResponseByJsonPath('$.data[0].labor_cost')[0]);
            }
            else
            {
            }

            $I->dontSeeResponseJsonMatchesJsonPath("$.data[{$count}]");

            $i ++;
        }
    }

    public function addItemsToWorkorder(ApiTester $I)
    {
        $I->sendPOST('sale-items', [
            'store_item_id' => $this->storeItemList[0]['id'],
            'work_order_id' => $this->workorderId,
            'on_receipt' => 1,
            'tax_exempt' => 0
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');

        $this->saleItemList[0] = $I->grabDataFromResponseByJsonPath('$.data')[0];

        $I->sendPOST('sale-items', [
            'store_item_id' => $this->storeItemList[1]['id'],
            'work_order_id' => $this->workorderId,
            'on_receipt' => 1,
            'tax_exempt' => 0
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');

        $this->saleItemList[1] = $I->grabDataFromResponseByJsonPath('$.data')[0];

        $I->sendGET("sale-items/{$this->saleItemList[0]['id']}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseContainsJson([
            'id' => $this->saleItemList[0]['id'],
            'work_order_id' => $this->workorderId
        ]);
    }

    public function editSaleItems(ApiTester $I)
    {
        $saleItem = [
            'price' => '79.99'
        ];

        $I->sendPUT("sale-items/{$this->saleItemList[1]['id']}", $saleItem);
        $I->seeResponseCodeIs(205);

        $I->sendGET("sale-items/{$this->saleItemList[1]['id']}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');

        $I->seeResponseContainsJson([
            'data' => [
                'id' => $this->storeItemList[1]['id'],
                'price' => $saleItem['price']
            ]
        ]);
    }

    public function checkWorkOrderNotesSoFar(ApiTester $I)
    {
        $workorderNoteCount = count($this->workorderNoteList);

        $I->sendGET("workorders/{$this->workorderId}/notes");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data[0].id');
        $I->dontSeeResponseJsonMatchesJsonPath("$.data[{$workorderNoteCount}]");

        $I->seeResponseContainsJson([
            'data' => $this->workorderNoteList
        ]);
    }

    public function checkInventoryLevels(ApiTester $I)
    {
        $count = count($this->storeItemList);
        $countMinusOne = $count - 1;

        $I->sendGET("storeitems?all=1");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath("$.data[{$countMinusOne}}].id");
        $I->dontSeeResponseJsonMatchesJsonPath("$.data[{$count}]");

        $I->seeResponseContainsJson([
            'data' => [
                [
                    'on_order' => '0',
                    'reserved' => '1',
                    'stock' => '-1'
                ],
                [
                    'on_order' => '0',
                    'reserved' => '1',
                    'stock' => '-1'
                ]
            ]
        ]);
    }

    public function createNewWorkOrderNote(ApiTester $I)
    {
        $workorderNote = [
            'public' => '1',
            'workorder_status_id' => '5',
            'next_update_time' => '1429118143',
            'notes' => 'Test Note'
        ];

        $I->sendPOST("workorders/{$this->workorderId}/notes", $workorderNote);
        $I->seeResponseCodeIs(201);
        $I->seeResponseJsonMatchesJsonPath('$.data');

        $workorderNote['next_update_time'] = '2015-04-15 17:15:43';

        $I->seeResponseContainsJson([
            'data' => $workorderNote
        ]);

        $newWorkorderNoteId = strval($I->grabDataFromResponseByJsonPath('$.data.id')[0]);

        $this->workorderNoteList[] = [ 'id' => $newWorkorderNoteId ];

        $this->checkWorkOrderNotesSoFar($I);
    }

    public function assignToTech(ApiTester $I)
    {
        $workorder = [
            'assigned_to_user_id' => '1'
        ];

        $I->sendPUT("workorders/{$this->workorderId}", $workorder);
        $I->seeResponseCodeIs(205);

        $I->sendGET("workorders/{$this->workorderId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data.id');

        $I->seeResponseContainsJson([
            'data' => $workorder
        ]);
    }

    public function makeCashPayment(ApiTester $I)
    {
//        $salePayment = [
//            'sale_id' => $this->saleId,
//            'sale_payment_type_id' => 2,
//            'amount' => '10'
//        ];
//
//        $I->sendPOST('sale-payments', $salePayment);
//        $I->seeResponseCodeIs(201);
//        $I->seeResponseJsonMatchesJsonPath('$.data');
//
//        $I->seeResponseContainsJson([
//            'data' => $salePayment
//        ]);
//
//        $newWorkorderNoteId = strval($I->grabDataFromResponseByJsonPath('$.data.id')[0]);
//
//        $this->workorderNoteList[] = [ 'id' => $newWorkorderNoteId ];
    }
}
