<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddStoreIdToPermissionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // todo revise onDelete for all foreign keys
        Schema::table(CreatePermissionUserTable::TABLENAME, function (Blueprint $table) {
                $table->integer('store_id')->unsigned()->index()->nullable();
                $table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
            });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(CreatePermissionUserTable::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['store_id']);
            });
    }

}
