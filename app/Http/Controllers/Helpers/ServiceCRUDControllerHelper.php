<?php namespace Biffy\Http\Controllers\Helpers;

trait ServiceCRUDControllerHelper
{
    use ServiceListControllerHelper,
        ServiceShowControllerHelper,
        ServiceStoreControllerHelper,
        ServiceUpdateControllerHelper,
        ServiceDestroyControllerHelper,
        ServiceSelectControllerHelper;
}