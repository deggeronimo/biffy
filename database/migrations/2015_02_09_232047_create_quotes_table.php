<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuotesTable extends Migration
{
	const TABLENAME = 'quotes';

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
				$table->unsignedInteger('customer_id')->nullable();
				$table->foreign('customer_id')->references('id')->on(CreateCustomersTable::TABLENAME)->onDelete('cascade');
                $table->decimal('subtotal', 13, 2);
                $table->decimal('taxes', 13, 2);
				$table->timestamps();
			}
		);

		Schema::table(
			CreateSalesTable::TABLENAME,
			function (Blueprint $table)
			{
				$table->unsignedInteger('quote_id')->nullable()->index();
				$table->foreign('quote_id')->references('id')->on(self::TABLENAME)->onDelete('cascade');
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
		Schema::table(
			CreateSalesTable::TABLENAME,
			function (Blueprint $table)
			{
				$table->dropForeign(['quote_id']);
			}
		);

		Schema::drop(self::TABLENAME);
	}
}
