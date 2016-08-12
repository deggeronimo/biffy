<?php

namespace Biffy\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $orm = 'Eloquent';

        $entities = [
            'AccountExpense',
            'ApiKey',
            'Appointment',
            'AppointmentBlackout',
            'BoardCategory',
            'BoardPost',
            'BoardPostRep',
            'BoardThread',
            'BodRecord',
            'ChecklistFunction',
            'ChecklistItem',
            'Company',
            'CompanyContact',
            'CompanyInstructions',
            'CompanySaleApproval',
            'CompanyStoreItem',
            'Config',
            'Customer',
            'CustomerNote',
            'Device',
            'DeviceApproval',
            'DeviceChecklist',
            'DeviceFamily',
            'DeviceItemChecklist',
            'DeviceManufacturer',
            'DeviceRepair',
            'DeviceRepairType',
            'DeviceRepairOption',
            'DeviceRepairOptionItem',
            'DeviceType',
            'DeviceTypeFilter',
            'EodRecord',
            'FileCategory',
            'FileSet',
            'File',
            'Feedback',
            'FeedbackCallLog',
            'FeedbackDoc',
            'FeedbackNote',
            'Group',
            'Inventory',
            'Invoice',
            'InvoicePayment',
            'Item',
            'Language',
            'LanguageKey',
            'LanguageString',
            'Lead',
            'MarketingLocation',
            'MarketingRun',
            'Quote',
            'Permission',
            'PermissionUser',
            'Project',
            'ProjectTask',
            'ProjectTaskComment',
            'ProjectTemplate',
            'PurchaseItem',
            'PurchaseOrder',
            'ReceiveItem',
            'ReceiveOrder',
            'Role',
            'Roster',
            'RosterRole',
            'Sale',
            'SaleItem',
            'SaleItemTax',
            'SalePayment',
            'Setting',
            'SupportTicketCategory',
            'SupportTicketStatus',
            'SupportTicket',
            'SupportTicketUpdate',
            'Store',
            'StoreConfig',
            'StoreItem',
            'StoreTax',
            'TimeClock',
            'UrlAlias',
            'User',
            'UserProfile',
            'UserSetting',
            'Vendor',
            'WebsiteFilter',
            'WebsiteFilterGroup',
            'WorkOrder',
            'WorkOrderNote',
            'WorkOrderCache',
            'WorkOrderStatus'
        ];

        foreach ($entities as $entity) {
            $this->app->bind(
                'Biffy\Entities\\' . $entity . '\\' . $entity . 'RepositoryInterface',
                'Biffy\Entities\\' . $entity . '\\' . $orm . $entity . 'Repository'
            );
        }
    }
}
