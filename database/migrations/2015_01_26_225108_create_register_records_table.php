<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegisterRecordsTable extends Migration
{
	const TABLENAME = 'register_records';

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
				$table->unsignedInteger('store_id');
				$table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
				$table->decimal('start_cash_amount', 13, 2);
				$table->decimal('end_cash_amount', 13, 2);
				$table->decimal('cash_transactions_total', 13, 2);
				$table->decimal('credit_transactions_total', 13, 2);
				$table->decimal('payout_transactions_total', 13, 2);
				$table->string('comments');
				$table->unsignedInteger('opened_by_user_id');
				$table->foreign('opened_by_user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
				$table->unsignedInteger('closed_by_user_id')->nullable();
				$table->foreign('closed_by_user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
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
