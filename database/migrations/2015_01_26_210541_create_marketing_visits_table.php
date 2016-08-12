<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingVisitsTable extends Migration
{
	const TABLENAME = 'marketing_visits';

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
				$table->integer('marketing_run_id')->unsigned()->index();
				$table->foreign('marketing_run_id')->references('id')->on(CreateMarketingRunsTable::TABLENAME)->onDelete('cascade');
				$table->integer('marketing_location_id')->unsigned()->index();
				$table->foreign('marketing_location_id')->references('id')->on(CreateMarketingLocationsTable::TABLENAME)->onDelete('cascade');
				$table->integer('marketing_run_type_id');
				$table->string('comments');
				$table->timestamps();

				$table->unique([ 'marketing_run_id', 'marketing_location_id' ]);
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
