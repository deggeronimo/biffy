<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppointmentsTable extends Migration
{
	const TABLENAME = 'appointments';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function(Blueprint $table)
		{
			$table->increments('id');
            $table->timestamp('time')->index();
            $table->unsignedInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on(CreateCustomersTable::TABLENAME)->onDelete('cascade');
			$table->unsignedInteger('appointment_status_id')->index();
			$table->foreign('appointment_status_id')->references('id')->on(CreateAppointmentStatusesTable::TABLENAME)->onDelete('cascade');
			$table->unsignedInteger('store_id')->index();
			$table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
            $table->string('issue');
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
		Schema::drop(self::TABLENAME);
	}
}