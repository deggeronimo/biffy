<?php

/** @var Illuminate\Routing\Router $router */
// todo add CSRF filter before appropriate routes

$router->group(['prefix' => 'api'], function ($router) {
        /** @var Illuminate\Routing\Router $router */
        $router->controller('auth', 'AuthController');
    });

$router->group(
    ['middleware' => ['Biffy\Http\Middleware\VerifyApiKey'], 'prefix' => 'api-web'],
    function ($router) {
        /** @var Illuminate\Routing\Router $router */
        // todo place website accessible api routes
        $router->resource('devicetypes', 'DeviceTypeController', ['only' => ['index', 'show']]);
        $router->resource('devicerepairs', 'DeviceRepairController', ['only' => ['index', 'show']]);
        $router->resource('websitefilters', 'WebsiteFilterController', ['only' => ['index', 'show']]);
        $router->resource('websitefiltergroups', 'WebsiteFilterGroupController', ['only' => ['index', 'show']]);
    }
);

$router->group(
    ['middleware' => ['Biffy\Http\Middleware\GoogleAuthenticated', 'Biffy\Http\Middleware\VerifyStoreId']],
    function ($router) { // todo 2 changes below once dingo/api is updated
        /** @var Illuminate\Routing\Router $router */
        $router->group( // change group back to api
            ['version' => 'v1', 'prefix' => 'api'], // remove prefix
            function ($router) {
                /** @var Illuminate\Routing\Router $router */
                $router->resource('users', 'UserController', ['only' => ['index', 'store', 'show', 'update']]);
                $router->get('employees', 'UserController@employees');
                $router->resource('permissions', 'PermissionController', ['only' => ['index', 'store', 'update', 'show', 'destroy']]);
                $router->resource('users.permissions', 'PermissionUserController', ['only' => ['index', 'store']]);
                $router->resource('roles', 'RoleController', ['only' => ['index', 'store', 'update', 'show', 'destroy']]);
                $router->resource('roles.permissions', 'PermissionRoleController', ['only' => ['update', 'destroy']]);
                $router->resource('users.roles', 'RoleUserController', ['only' => ['update']]);
                $router->resource('groups', 'GroupController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('items', 'ItemController', ['only' => ['index', 'store', 'show', 'update']]);
                $router->resource('stores', 'StoreController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('storeitems', 'StoreItemController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('inventory', 'InventoryController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('customers', 'CustomerController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('sales', 'SaleController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('config', 'ConfigController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('store-config', 'StoreConfigController', ['only' => ['index', 'store']]);
                $router->resource('sale-items', 'SaleItemController', ['only' => ['show', 'store', 'update', 'destroy']]);
                $router->resource('store-taxes', 'StoreTaxController', ['only' => ['index', 'store', 'update']]);
                $router->resource('taxes', 'StoreTaxController', ['only' => ['update', 'destroy']]);
                $router->controller('timeclock', 'TimeClockController');
                $router->resource('vendors', 'VendorController', ['only' => ['index','show','store','update','destroy']]);
                $router->resource('workorders', 'WorkOrderController', ['only' => ['index','show','store','update','destroy']]);
                $router->resource('workorders.notes', 'WorkOrderNoteController', ['only' => ['index','show','store']]);
                $router->resource('leads', 'LeadController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('devices', 'DeviceController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
                $router->resource('purchase', 'PurchaseOrderController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
                $router->resource('purchaseitem', 'PurchaseItemController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
                $router->resource('purchaseitem.receive', 'ReceiveItemController', ['only' => ['store', 'destroy']]);
                $router->resource('customernotes', 'CustomerNoteController', ['only' => ['index', 'show', 'store']]);
                $router->resource('companies', 'CompanyController', ['only' => ['index', 'store', 'show', 'update', 'destroy', 'select']]);
                $router->resource('companies.contacts', 'CompanyContactController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('companies.instructions', 'CompanyInstructionsController', ['only'=>['index', 'show', 'update']]);
                $router->resource('companies.saleapprovals', 'CompanySaleApprovalController', ['only'=>['index','update']]);
                $router->resource('companies.storeitems', 'CompanyStoreItemController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('itemcategories', 'ItemCategoryController', ['only' => ['index']]);
                $router->resource('sale-payments', 'SalePaymentController', ['only' => ['store', 'destroy']]);
                $router->resource('userselect', 'UserSelectController', ['only' => ['select']]);
                $router->resource('supportticketcategories', 'SupportTicketCategoryController', ['only' => ['index', 'store', 'show', 'update', 'destroy', 'select']]);
                $router->resource('supportticketstatuses', 'SupportTicketStatusController', ['only' => ['index', 'store', 'show', 'update', 'destroy', 'select']]);
                $router->resource('supporttickets', 'SupportTicketController', ['only' => ['index', 'store', 'show', 'update', 'destroy', 'select']]);
                $router->resource('supporttickets.updates', 'SupportTicketUpdateController', ['only' => ['index', 'store', 'show', 'update', 'destroy', 'select']]);
                $router->resource('filecategories', 'FileCategoryController', ['only' => ['index', 'store', 'show', 'update', 'destroy', 'select']]);
                $router->resource('filesets', 'FileSetController', ['only' => ['index', 'store', 'show', 'update', 'destroy', 'select']]);
                $router->resource('filesets.files', 'FileController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->post('file/upload', 'FileController@postUpload');
                $router->resource('devicechecklists', 'DeviceChecklistController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('deviceitemchecklists', 'DeviceItemChecklistController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('devicetypes', 'DeviceTypeController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('devicerepairs', 'DeviceRepairController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('devicerepairtypes', 'DeviceRepairTypeController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('deviceapprovals', 'DeviceApprovalController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('devicerepairs.items', 'DeviceRepairItemController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('devicerepairoptions', 'DeviceRepairOptionController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('devicefamilies', 'DeviceFamilyController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('devicemanufacturers', 'DeviceManufacturerController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('feedback', 'FeedbackController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('feedbackdocs', 'FeedbackDocController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('feedbacknotes', 'FeedbackNoteController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('feedbackcalllogs', 'FeedbackCallLogController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('rosterroles', 'RosterRoleController', ['only' => ['index', 'store', 'show', 'update', 'destroy', 'select']]);
                $router->resource('appointments', 'AppointmentController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('appointmentblackouts', 'AppointmentBlackoutController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('rosters', 'RosterController', ['only' => ['index', 'store', 'show', 'update', 'destroy', 'select']]);
                $router->resource('checklistfunctions', 'ChecklistFunctionController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('checklistitems', 'ChecklistItemController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('accountexpenses', 'AccountExpenseController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('marketinglocations', 'MarketingLocationController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('marketingruns', 'MarketingRunController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('invoices', 'InvoiceController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('invoice-payments', 'InvoicePaymentController', ['only' => ['store', 'destroy']]);
                $router->resource('bodrecords', 'BodRecordController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('eodrecords', 'EodRecordController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('quotes', 'QuoteController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->controller('boards', 'MessageBoardController');
                $router->controller('projects', 'ProjectController');
                $router->resource('websitefilters', 'WebsiteFilterController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('websitefiltergroups', 'WebsiteFilterGroupController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('settings', 'SettingController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
                $router->resource('user-settings', 'UserSettingController', ['only' => ['index']]);
                $router->resource('workorder-statuses', 'WorkorderStatusController', ['only' => ['index']]);

                $router->get('users/{user_id}/stores', 'UserSelectController@stores');
                $router->get('stores/{store_id}/users', 'StoreSelectController@users');
                $router->get('invoices/{invoice_id}/sales', 'InvoiceSelectController@sales');

                $router->get('invoices/{invoice_id}/payments', 'InvoiceSelectController@payments');

                $router->get('marketinglocations/{latitude}/{longitude}', 'MarketingLocationController@gps');

                $router->resource('languages', 'LanguageController', ['only'=>['index', 'store']]);
                $router->controller('languagekey', 'LanguageKeyController');
                $router->resource('languagekeys.strings', 'LanguageStringController', ['only'=>['index', 'store', 'show', 'update']]);

                //The following resources don't actually exist in the database as a Model.
                $router->resource('recommendeditems/{workOrderId}', 'RecommendedItemController', ['only' => ['index']]);
                $router->resource('missingitems/{search}', 'MissingItemController', ['only' => ['index']]);
                $router->resource('autoorder', 'AutoOrderController', ['only' => ['index']]);
                $router->resource('marketingrunstate', 'MarketingRunStateController', ['only' => 'show']);

                $router->controller('key', 'ApiKeyController');

                $router->controller('pos', 'PosController');

                $router->group(
                    ['namespace' => 'Reports', 'prefix' => 'reports'],
                    function ($router)
                    {
                        /** @var Illuminate\Routing\Router $router */
                        $router->get('inventory/{reportType}/{startDate}/{endDate}', 'InventoryReporter@doReport');
                        $router->get('sales/{reportType}/{startDate}/{endDate}', 'SalesReporter@doReport');
                        $router->get('timeclock/{reportType}/{startDate}/{endDate}', 'TimeClockReporter@doReport');
                    }
                );
                $router->controller('twilio', 'TwilioController');
                $router->controller('contact', 'CustomerContactController');
                $router->controller('customer', 'CustomerController');
            }
        );
    }
);


//fail all apis at this point
$router->any('api/{path}', function($path) {
    return Response::json(['messages' => ['error' => ['Action could not be completed due to missing route: ' . $path]]], 404);
})->where('path', '.*');

//export routes group
$router->group(
    ['middleware' => 'Biffy\Http\Middleware\GoogleAuthenticated'],
    function ($router) {
        /** @var Illuminate\Routing\Router $router */
        $router->group(
            ['prefix' => 'export'],
            function ($router) {
                /** @var Illuminate\Routing\Router $router */
                $router->get('customers', 'CustomerController@export');
                $router->get('leads', 'LeadController@export');
                $router->get('companies', 'CompanyController@export');
            }
        );
    }
);

//fail all GET app,src,assets,components,bower_components urls with 404
$router->get('app/{path?}', function($path) {
    return Response::json(['messages' => ['error' => ['Action could not be completed due to missing route']]], 404);
})->where('path', '.*');

$router->get('assets/{path?}', function($path) {
    return Response::json(['messages' => ['error' => ['Action could not be completed due to missing route']]], 404);
})->where('path', '.*');

$router->get('bower_components/{path?}', function($path) {
    return Response::json(['messages' => ['error' => ['Action could not be completed due to missing route']]], 404);
})->where('path', '.*');

//Required only in development
$router->get('src/{path?}', function($path) {
    return Response::json(['messages' => ['error' => ['Action could not be completed due to missing route']]], 404);
})->where('path', '.*');

//Required only in development
$router->get('components/{path?}', function($path) {
    return Response::json(['messages' => ['error' => ['Action could not be completed due to missing route']]], 404);
})->where('path', '.*');

//Load client application through index.html
$router->get('{all}', 'IndexController@client')->where('all', '.*');
