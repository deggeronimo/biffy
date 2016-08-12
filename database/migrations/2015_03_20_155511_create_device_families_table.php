<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceFamiliesTable extends Migration
{
    const TABLENAME = 'device_families';

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
                $table->unsignedInteger('device_family_id')->nullable()->index()->default(null)->after('device_manufacturer_id');
                $table->foreign('device_family_id')->references('id')->on(self::TABLENAME)->onDelete('cascade');
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
                $table->dropForeign(['device_family_id']);
            }
        );

        Schema::drop(self::TABLENAME);
	}
}
