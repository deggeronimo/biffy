<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddDeviceTypeFilterToDeviceTypesTable extends Migration
{
    const TABLENAME = 'device_type_filter';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table(
            CreateDeviceTypeTable::TABLENAME,
            function(Blueprint $table)
            {
                $table->unsignedInteger('device_type_filter_id')->nullable()->after('deleted_at');
                $table->foreign('device_type_filter_id')->references('id')->on(CreateDeviceTypeTable::TABLENAME);
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
            function(Blueprint $table)
            {
                $table->dropForeign(['device_type_filter_id']);
                $table->dropColumn('device_type_filter_id');
            }
        );
	}
}
