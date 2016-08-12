<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
	const TABLENAME = 'invoices';

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
				$table->decimal('subtotal', 13, 2)->default('0.0');
				$table->decimal('taxes', 13, 2)->default('0.0');
				$table->decimal('payments', 13, 2)->default('0.0');
				$table->decimal('adjustments', 13, 2)->default('0.0');
				$table->unsignedInteger('store_id')->index()->nullable();
				$table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
				$table->unsignedInteger('customer_id')->index()->nullable();
				$table->foreign('customer_id')->references('id')->on(CreateCustomersTable::TABLENAME)->onDelete('cascade');
				$table->unsignedInteger('company_id')->index()->nullable();
				$table->foreign('company_id')->references('id')->on(CreateCompaniesTable::TABLENAME)->onDelete('cascade');
				$table->string('details');
				$table->timestamps();
			}
		);

        Schema::table(
            CreateSalesTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->unsignedInteger('invoice_id')->nullable()->index();
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
