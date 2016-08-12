<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDevicesTable extends Migration
{
    const TABLENAME = 'devices';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, /**
             * @param Blueprint $table
             */
        function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name');
            $table->string('passcode');
            $table->string('serial');
            $table->string('serial_type');
            $table->unsignedInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on(CreateCustomersTable::TABLENAME)->onDelete('cascade');
            $table->unsignedInteger('device_type_id')->index();
            $table->foreign('device_type_id')->references('id')->on(CreateDeviceTypeTable::TABLENAME)->onDelete('cascade');
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table(self::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['customer_id']);
            });
		Schema::drop(self::TABLENAME);
	}
}
