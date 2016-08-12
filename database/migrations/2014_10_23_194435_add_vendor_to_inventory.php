<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddVendorToInventory extends Migration {
    public function up()
    {
        Schema::table(CreateInventoryTable::TABLENAME, function (Blueprint $table) {
                $table->integer('vendor_id')->unsigned()->index()->nullable();
                $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            });
    }

    public function down()
    {
        Schema::table(CreateInventoryTable::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['vendor_id']);
            });
    }
}