<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMarketingLocationsTable extends Migration
{
	const TABLENAME = 'marketing_locations';

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
				$table->unsignedInteger('marketing_location_type_id');
				$table->foreign('marketing_location_type_id')->references('id')->on(CreateMarketingLocationTypesTable::TABLENAME)->onDelete('cascade');
				$table->decimal('latitude', 18, 15)->index();
				$table->decimal('longitude', 18, 15)->index();
				$table->string('address');
				$table->string('phone');
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
