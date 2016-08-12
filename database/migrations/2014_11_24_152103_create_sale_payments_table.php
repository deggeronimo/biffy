<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalePaymentsTable extends Migration
{
    const TABLENAME = 'sale_payments';
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
            $table->unsignedInteger('sale_id')->index();
            $table->foreign('sale_id')->references('id')->on(CreateSalesTable::TABLENAME)->onDelete('cascade');
            $table->unsignedInteger('sale_payment_type_id')->index();
            $table->foreign('sale_payment_type_id')->references('id')->on(CreateSalePaymentTypesTable::TABLENAME)->onDelete('cascade');
            $table->decimal('amount', 13, 2);
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
