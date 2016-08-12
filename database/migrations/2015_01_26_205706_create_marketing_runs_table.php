<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMarketingRunsTable extends Migration
{
	const TABLENAME = 'marketing_runs';

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
				$table->boolean('stopped')->default(0);
				$table->unsignedInteger('store_id');
				$table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
				$table->unsignedInteger('user_id');
				$table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
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
