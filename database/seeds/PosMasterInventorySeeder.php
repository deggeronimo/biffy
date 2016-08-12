<?php

use Illuminate\Support\Facades\DB;

class PosMasterInventorySeeder extends AbstractSqlFileSeeder
{
    protected $filename = 'pos_master_inventory.sql';

    public function complete()
    {
        //This fixes an issue with the table where the category_id foreign key should be set to the id of the master
        //category table rather than the category_id. (Referential Integrity)
        DB::statement('update pos_master_inventory set category_id = (select pos_master_category.id from pos_master_category where pos_master_category.category_id = pos_master_inventory.category_id) where exists (select * from pos_master_category where pos_master_category.category_id = pos_master_inventory.category_id);');
    }
}