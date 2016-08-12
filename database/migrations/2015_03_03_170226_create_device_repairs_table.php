<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceRepairsTable extends Migration
{
    const TABLENAME = 'device_repairs';

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
                $table->unsignedInteger('device_type_id')->index()->nullable();
                $table->foreign('device_type_id')->references('id')->on(CreateDeviceTypeTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('item_id')->nullable();
                $table->foreign('item_id')->references('id')->on(CreateItemsTable::TABLENAME);
                $table->softDeletes();
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
