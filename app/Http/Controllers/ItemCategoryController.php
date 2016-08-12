<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\ItemCategory\ItemCategory;

/**
 * Class ItemCategoryController
 * @package Biffy\Http\Controllers
 */
class ItemCategoryController extends ApiController
{
    /**
     * @return mixed
     */
    public function index()
    {
        return $this->data(ItemCategory::all()->toArray())->statusOk()->respond();
    }
}