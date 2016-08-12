<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceApprovalsTable extends Migration
{
    const TABLENAME = 'device_approvals';

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
                $table->string('scrub_device_name')->index();
                $table->string('scrub_manufacturer_name')->index();
                $table->string('scrub_carrier_name')->index();
                $table->string('device_name');
                $table->string('manufacturer_name');
                $table->string('carrier_name');
                $table->boolean('approved')->default(false);
                $table->timestamps();

                $table->unique([ 'scrub_device_name', 'scrub_manufacturer_name', 'scrub_carrier_name' ], 'device_approvals_unique');
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
