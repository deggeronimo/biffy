<?php namespace Biffy\Entities\SaleItemTax;

use Biffy\Entities\AbstractRepositoryInterface;

interface SaleItemTaxRepositoryInterface extends AbstractRepositoryInterface
{
    public function deleteTaxItemsWithSaleItemId($id);
}