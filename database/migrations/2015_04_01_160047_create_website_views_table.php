<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebsiteViewsTable extends Migration
{
    const TABLENAME = 'website_views';

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
                $table->string('session_id');
                $table->unsignedInteger('device_type_id')->nullable();
                $table->foreign('device_type_id')->references('id')->on(CreateDeviceTypeTable::TABLENAME);
                $table->unsignedInteger('device_repair_id')->nullable();
                $table->foreign('device_repair_id')->references('id')->on(CreateDeviceRepairsTable::TABLENAME);
                $table->timestamps();
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
