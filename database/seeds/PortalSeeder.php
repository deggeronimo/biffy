<?php

class PortalSeeder extends BaseSeeder
{
    protected $truncateTables = [
        'account_expense_categories',
        'appointment_blackouts',
        'appointment_statuses',
        'checklist_functions',
        'checklist_items',
        'config',
        'device_families',
        'device_manufacturers',
        'device_repairs',
        'device_types',
        'feedback_doctypes',
        'feedback_statuses',
        'file_categories',
        'items',
        'languages',
        'language_keys',
        'language_strings',
        'marketing_location_types',
        'permissions',
        'permission_role',
        'permission_user',
        'roster_roles',
        'sale_payment_types',
        'settings',
        'store_config',
        'store_items',
        'support_ticket_categories',
        'support_ticket_statuses',
        'user_settings',
        'workorder_statuses'
    ];

    public function run()
    {
        $this->cleanDatabase();

        if (env('fake_user', false)) {
            $this->truncateTables = ['groups', 'group_user', 'stores', 'users'];
            $this->cleanDatabase();

            $this->call('FakeGoogleImportSeeder');
        }

        $this->call('LanguagesTableSeeder');

        $this->call('PermissionsTableSeeder');
        $this->call('ConfigTableSeeder');
        $this->call('SettingsTableSeeder');
        $this->call('VendorsTableSeeder');

        $this->call('SupportTicketCategoriesTableSeeder');
        $this->call('SupportTicketStatusesTableSeeder');

        $this->call('FileCategoriesTableSeeder');

        $this->call('RosterRolesTableSeeder');

        $this->call('FeedbackDoctypesSeeder');
        $this->call('FeedbackStatusesSeeder');

        $this->call('AppointmentStatusesTableSeeder');
        $this->call('AppointmentBlackoutsTableSeeder');

        $this->call('AccountExpenseCategoriesTableSeeder');

        $this->call('MarketingRunLocationTypesTableSeeder');

        $this->call('SalePaymentTypesTableSeeder');
        $this->call('WorkorderStatusesSeeder');

        $this->call('DeviceFamiliesTableSeeder2');
        $this->call('DeviceManufacturersTableSeeder2');
        $this->call('ItemsTableSeeder2');
        $this->call('DeviceTypesTableSeeder2');
        $this->call('DeviceRepairTypesTableSeeder');
        $this->call('DeviceRepairsTableSeeder2');

        $this->call('ChecklistFunctionTableSeeder');
        $this->call('DeviceChecklistsTableSeeder');
        $this->call('ChecklistItemsTableSeeder');
        $this->call('DeviceItemChecklistsTableSeeder');
        $this->call('DeviceRepairOptionItemsTableSeeder');
    }
} 