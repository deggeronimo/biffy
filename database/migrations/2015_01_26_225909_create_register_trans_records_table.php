<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegisterTransRecordsTable extends Migration
{
	const TABLENAME = 'register_trans_records';

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
				$table->unsignedInteger('sale_payment_id')->index()->nullable();
				$table->foreign('sale_payment_id')->references('id')->on(CreateSalePaymentsTable::TABLENAME)->onDelete('cascade');
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
