<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppointmentBlackoutsTable extends Migration
{
	const TABLENAME = 'appointment_blackouts';

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
			$table->unsignedInteger('store_id')->index()->nullable();
			$table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
			$table->integer('year')->index()->nullable();
			$table->integer('day_of_year')->index()->nullable();
			$table->integer('day_of_week')->index()->nullable();
			$table->integer('hour_of_day')->index()->nullable();
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