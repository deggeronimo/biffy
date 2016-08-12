<?php namespace Biffy\Entities\CompanyStoreItem;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentCompanyStoreItemRepository extends EloquentAbstractRepository implements CompanyStoreItemRepositoryInterface
{
    /**
     * @var array
     */
    protected $filters = [
        'company_id' => ['company_id = ?', ':value'],
        'store_item_id' => ['store_item_id = ?', ':value'],
    ];

    /**
     * @param CompanyStoreItem $model
     */
    public function __construct(CompanyStoreItem $model)
    {
        $this->model = $model;

        $this->with([ 'storeItem', 'storeItem.item', 'storeItem.item.deviceType' ]);
    }
}