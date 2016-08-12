<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountExpensesTable extends Migration
{
	const TABLENAME = 'account_expenses';

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
				$table->decimal('amount', 13, 2);
				$table->unsignedInteger('vendor_id');
				$table->foreign('vendor_id')->references('id')->on(CreateVendorsTable::TABLENAME)->onDelete('cascade');
				$table->unsignedInteger('store_id');
				$table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
				$table->unsignedInteger('user_id');
				$table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
				$table->string('filename');
				$table->string('comments');
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
