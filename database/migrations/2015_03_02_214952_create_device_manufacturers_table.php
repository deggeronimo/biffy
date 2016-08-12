<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceManufacturersTable extends Migration
{
    const TABLENAME = 'device_manufacturers';

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
                $table->string('name');
            }
        );

        Schema::table(
            CreateDeviceTypeTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->unsignedInteger('device_manufacturer_id')->nullable()->index()->default(null);
                $table->foreign('device_manufacturer_id')->references('id')->on(self::TABLENAME)->onDelete('cascade');
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
        Schema::table(
            CreateDeviceTypeTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->dropForeign(['device_manufacturer_id']);
                $table->dropColumn('device_manufacturer_id');
            }
        );

        Schema::drop(self::TABLENAME);
	}
}
