<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceRepairOptionsItemsTable extends Migration
{
    const TABLENAME = 'device_repair_option_items';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            self::TABLENAME,
            function(Blueprint $table)
            {
                $table->increments('id');
                $table->unsignedInteger('device_repair_id');
                $table->foreign('device_repair_id')->references('id')->on(CreateDeviceRepairsTable::TABLENAME);
                $table->unsignedInteger('device_repair_option_id');
                $table->foreign('device_repair_option_id')->references('id')->on(CreateDeviceRepairOptionsTable::TABLENAME);
                $table->unsignedInteger('item_id')->nullable();
                $table->foreign('item_id')->references('id')->on(CreateItemsTable::TABLENAME);
                $table->unsignedInteger('estimated_cost_language_key_id');
                $table->foreign('estimated_cost_language_key_id', 'device_repair_option_items_ec_language_key_id_foreign')->references('id')->on(CreateLanguageKeysTable::TABLENAME);
                $table->string('option_value');
                $table->string('image');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(self::TABLENAME);
    }
}
