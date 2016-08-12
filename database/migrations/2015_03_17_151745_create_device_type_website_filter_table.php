<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceTypeWebsiteFilterTable extends Migration
{
    const TABLENAME = 'website_filter_device_types';
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
                $table->unsignedInteger('website_filter_id');
                $table->foreign('website_filter_id')->references('id')->on(CreateWebsiteFiltersTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('device_type_id');
                $table->foreign('device_type_id')->references('id')->on(CreateDeviceTypeTable::TABLENAME)->onDelete('cascade');
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
