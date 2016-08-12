<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSaleTable extends Migration
{
	const TABLENAME = 'invoice_sale';

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
				$table->integer('invoice_id')->unsigned()->index();
				$table->foreign('invoice_id')->references('id')->on(CreateInvoicesTable::TABLENAME)->onDelete('cascade');
				$table->integer('sale_id')->unsigned()->index();
				$table->foreign('sale_id')->references('id')->on(CreateSalesTable::TABLENAME)->onDelete('cascade');
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
