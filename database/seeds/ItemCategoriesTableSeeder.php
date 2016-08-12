<?php

use Biffy\Entities\ItemCategory\ItemCategory;

class ItemCategoriesTableSeeder extends AbstractExistingTableSeeder
{
    protected $mappings = [
        'name' => 'name',
        'parent_id' => 'item_category_id'
    ];

    protected $select = 'name, parent_id';

    protected $sourceTable = 'pos_master_category';

    public function __construct(ItemCategory $model)
    {
        $this->model = $model;
    }

    public function complete()
    {
    }
}