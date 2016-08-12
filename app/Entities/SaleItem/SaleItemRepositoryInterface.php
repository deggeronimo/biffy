<?php namespace Biffy\Entities\SaleItem;

use Biffy\Entities\AbstractRepositoryInterface;

interface SaleItemRepositoryInterface extends AbstractRepositoryInterface
{
    public function getAllWhereSaleCompleted($startDate, $endDate);
    public function getAllWhereSaleNotCompleted($startDate, $endDate);
    public function getAllForStoreBetweenDates($select, $storeId, $startDate, $endDate);
}