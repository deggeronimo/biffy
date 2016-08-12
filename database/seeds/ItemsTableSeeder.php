<?php

use Biffy\Entities\Item\Item;
use Biffy\Entities\Store\Store;

class ItemsTableSeeder extends AbstractExistingTableSeeder
{
    protected $joins = [
//        'distroproduct on distroproduct.model = pos_master_inventory.item_number'
    ];

    protected $mappings = [
        'name' => 'name',
        'category_id' => 'device_type_id',
        'item_number' => 'item_number',
        'unit_price' => 'unit_price',
        'labor_cost' => 'labor_cost',
//        'price' => 'distro_price',
        'item_type_id' => 'item_type_id',
        'vendor_id' => 'vendor_id'
    ];

    protected $select = 'pos_master_inventory.name, category_id, pos_master_inventory.item_number, unit_price, item_type, labor_cost';//, distroproduct.price';

    protected $sourceTable = 'pos_master_inventory';

    private $itemTypes = [ '', 'inventory', 'service' ];

    public function __construct(Item $model)
    {
        $this->model = $model;
        $this->storeIds = [ Store::where('name', '=', 'Dev')->first()->id ];//Store::lists('id');
    }

    public function afterInsert( & $newRow)
    {
        $newRow->stores()->attach($this->storeIds, [
            'stock' => 0,
            'unit_price' => $newRow->unit_price,
            'labor_cost' => $newRow->labor_cost
        ]);

        return false;
    }

    public function beforeInsert ( & $oldRow)
    {
        $itemType = $oldRow->item_type;

        for ($i = 1; $i < count($this->itemTypes); $i ++)
        {
            if ($itemType == $this->itemTypes[$i])
            {
                $oldRow->item_type_id = $i;
            }
        }

//        if (is_null($oldRow->price))
        {
            $oldRow->price = 0;
        }

        $oldRow->vendor_id = 1;

        return $oldRow;
    }
}
