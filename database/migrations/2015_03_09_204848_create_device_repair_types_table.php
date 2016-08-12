<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceRepairTypesTable extends Migration
{
    const TABLENAME = 'device_repair_types';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create(
            self::TABLENAME,
            function (Blueprint $table)
            {
                $table->increments('id');
                $table->string('image_overlay');
                $table->string('class');
                $table->unsignedInteger('sort_order')->default(0);
                $table->softDeletes();
            }
        );

        Schema::table(
            CreateDeviceRepairsTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->unsignedInteger('device_repair_type_id')->after('device_type_id')->nullable()->index();
                $table->foreign('device_repair_type_id')->references('id')->on(CreateDeviceRepairTypesTable::TABLENAME)->onDelete('cascade');
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
            CreateDeviceRepairsTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->dropForeign(['device_repair_type_id']);
            }
        );

        Schema::drop(self::TABLENAME);
	}
}
