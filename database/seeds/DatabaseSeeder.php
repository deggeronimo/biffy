<?php

class DatabaseSeeder extends \Illuminate\Database\Seeder
{
    private $tables = [
//        'account_expenses',
//        'account_expense_categories',
//        'actions',
//        'api_keys',
//        'appointments',
//        'appointment_blackouts',
//        'appointment_statuses',
//        'bod_records',
//        'checklist_functions',
//        'checklist_items',
//        'companies',
//        'company_contacts',
//        'company_instructions',
//        'company_sale_approvals',
//        'company_store_items',
//        'config',
//        'customers',
//        'customer_notes',
//        'devices',
//        'device_approvals',
//        'device_checklists',
//        'device_families',
//        'device_item_checklists',
//        'device_manufacturers',
//        'device_repairs',
//        'device_repair_options',
//        'device_repair_option_items',
//        'device_repair_types',
//        'device_types',
//        'distroproduct',
//        'eod_records',
//        'feedbacks',
//        'feedback_call_logs',
//        'feedback_docs',
//        'feedback_doctypes',
//        'feedback_notes',
//        'feedback_statuses',
//        'files',
//        'file_categories',
//        'file_sets',
//        'inventory',
//        'invoices',
//        'invoice_sale',
//        'items',
//        'languages',
//        'language_keys',
//        'language_strings',
//        'leads',
//        'marketing_locations',
//        'marketing_location_types',
//        'marketing_runs',
//        'marketing_visits',
//        'permissions',
//        'permission_role',
//        'permission_user',
//        'pos_master_category',
//        'pos_master_inventory',
//        'purchase_items',
//        'purchase_orders',
//        'quotes',
//        'receive_items',
//        'register_payouts',
//        'register_records',
//        'register_trans_records',
//        'register_trans_types',
//        'roles',
//        'rosters',
//        'roster_roles',
//        'sales',
//        'sale_items',
//        'sale_item_taxes',
//        'sale_payments',
//        'sale_payment_types',
//        'store_config',
//        'store_items',
//        'store_taxes',
//        'suppliers',
//        'support_tickets',
//        'support_ticket_categories',
//        'support_ticket_statuses',
//        'support_ticket_updates',
//        'support_ticket_watcher',
//        'timeclock',
//        'vendors',
//        'website_category',
//        'website_category_description',
//        'website_category_to_store',
//        'website_filters',
//        'website_filter_description',
//        'website_filter_device_types',
//        'website_filter_groups',
//        'website_product',
//        'website_product_description',
//        'website_product_to_category',
//        'workorders',
//        'workorder_caches',
//        'workorder_notes',
//        'workorder_statuses'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('PortalSeeder');
        return;
        $this->cleanDatabase();

//        $this->call('ItemsTable_OldItemIdSeeder');

//        if (env('fake_user', false)) {
//            $this->call('FakeGoogleImportSeeder');
//        }

        //1) Import tables necessary to log into and make portal usable.
//        $this->call('PermissionsTableSeeder');
//        $this->call('ConfigTableSeeder');

        //2) Import tables necessary for remote use of portal database (e.g. website)
//        $this->call('ApiKeysTableSeeder');

//        $this->call('LanguagesTableSeeder');

//        $this->call('WebsiteFiltersTableSeeder');
//        $this->call('WebsiteFilterGroupsTableSeeder');
//        $this->call('WebsiteFilterDescriptionTableSeeder');

//        $this->call('WebsiteCategoryTableSeeder');
//        $this->call('WebsiteCategoryDescriptionTableSeeder');
//        $this->call('WebsiteCategoryToStoreTableSeeder');

//        $this->call('WebsiteProductTableSeeder');
//        $this->call('WebsiteProductDescriptionTableSeeder');
//        $this->call('WebsiteProductToCategorySeeder');

//        $this->call('WebsiteFilterDeviceTypesTableSeeder');

//        $this->call('DeviceManufacturersTableSeeder');
//        $this->call('DeviceFamiliesTableSeeder');
//        $this->call('DeviceTypesTableSeeder');

//        $this->call('DeviceRepairTypesTableSeeder');
//        $this->call('DeviceRepairsTableSeeder');

        //3) Import data from Portal 2 database and Website 2 database
//        $this->call('PosMasterCategorySeeder');
//        $this->call('PosMasterInventorySeeder');
//        $this->call('PosDistroProductSeeder');

        //4) Convert data from Portal 2 database to Portal 3 database
//        $this->call('ItemsTableSeeder');

//        $this->call('DeviceRepairOptionsTableSeeder');
//        $this->call('DeviceRepairOptionItemsTableSeeder');

        //5) Add more stuff
//        $this->call('CustomersTableSeeder');

//        $this->call('TimeClockTableSeeder');
//        $this->call('VendorsTableSeeder');

//        $this->call('WorkorderStatusesSeeder');
//        $this->call('ActionsSeeder');

//        $this->call('DevicesSeeder');

//        $this->call('ChecklistFunctionTableSeeder');
//        $this->call('DeviceChecklistsTableSeeder');
//        $this->call('ChecklistItemsTableSeeder');
//        $this->call('DeviceItemChecklistsTableSeeder');

//        $this->call('LeadsTableSeeder');
//        $this->call('CompaniesTableSeeder');
//        $this->call('CompanyStoreItemsTableSeeder');
//        $this->call('CompanyContactsTableSeeder');

//        $this->call('StoreTaxesSeeder');

//        $this->call('SupportTicketCategoriesTableSeeder');
//        $this->call('SupportTicketStatusesTableSeeder');
//        $this->call('SupportTicketsTableSeeder');
//        $this->call('SupportTicketUpdatesTableSeeder');

//        $this->call('FileCategoriesTableSeeder');
//        $this->call('FileSetsTableSeeder');

//        $this->call('FeedbackDoctypesSeeder');
//        $this->call('FeedbackStatusesSeeder');

//        $this->call('FeedbackSeeder');

//        $this->call('FeedbackDocsSeeder');
//        $this->call('FeedbackNotesSeeder');
//        $this->call('FeedbackCallLogsSeeder');

//        $this->call('RosterRolesTableSeeder');
//        $this->call('RostersTableSeeder');

//        $this->call('AppointmentStatusesTableSeeder');
//        $this->call('AppointmentBlackoutsTableSeeder');

//        $this->call('AccountExpenseCategoriesTableSeeder');

//        $this->call('MarketingRunLocationTypesTableSeeder');
//        $this->call('MarketingLocationsSeeder');

//        $this->call('SalePaymentTypesTableSeeder');

        $this->call('VendorsTableSeeder');

        $this->call('WorkorderStatusesSeeder');
        $this->call('ActionsSeeder');

        $this->call('ChecklistFunctionTableSeeder');
        $this->call('DeviceChecklistsTableSeeder');
        $this->call('ChecklistItemsTableSeeder');
        $this->call('DeviceItemChecklistsTableSeeder');

        $this->call('StoreTaxesSeeder');
    }
}
