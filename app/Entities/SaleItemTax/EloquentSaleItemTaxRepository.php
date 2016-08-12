<?php namespace Biffy\Entities\SaleItemTax;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentSaleItemTaxRepository
 * @package Biffy\Entities\SaleItemTax
 */
class EloquentSaleItemTaxRepository extends EloquentAbstractRepository implements SaleItemTaxRepositoryInterface
{
    /**
     * @param SaleItemTax $model
     */
    public function __construct(SaleItemTax $model)
    {
        $this->model = $model;
    }

    public function deleteTaxItemsWithSaleItemId($id)
    {
        $query = $this->make();

        $taxIds = $query->where('sale_item_id', '=', $id)->get();

        $this->model->destroy($taxIds->toArray());
    }
}