<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddWorkorderIdToSaleItems extends Migration
{
    public function up()
    {
        Schema::table(CreateSaleItemsTable::TABLENAME, function (Blueprint $table) {
                $table->unsignedInteger('work_order_id')->after('sale_id')->nullable()->index();
                $table->foreign('work_order_id')->references('id')->on(CreateWorkordersTable::TABLENAME)->onDelete('cascade');
            });
    }

    public function down()
    {
        Schema::table(CreateSaleItemsTable::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['work_order_id']);
            });
    }
}