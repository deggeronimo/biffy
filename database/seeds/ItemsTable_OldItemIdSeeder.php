<?php

class ItemsTable_OldItemIdSeeder extends \Illuminate\Database\Seeder
{
    public function __construct()
    {

    }

    public function run()
    {
        $itemList = DB::select(DB::raw('SELECT `item_id`, `name` FROM `ubif_portal`.`pos_master_inventory`'));

        foreach ($itemList as $item)
        {
            DB::statement("UPDATE items SET `old_item_id` = ? WHERE `name` = ?", [ $item->item_id, $item->name ]);
        }

        $itemList = DB::select(DB::raw('SELECT `id` FROM `items` where `id` != `old_item_id`'));

        $n = count($itemList);

        echo("There are {$n} items whose ids do not match their old item ids!");
    }
}