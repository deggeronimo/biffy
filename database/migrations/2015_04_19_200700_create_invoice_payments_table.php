<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicePaymentsTable extends Migration
{
    const TABLENAME = 'invoice_payments';

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
                $table->unsignedInteger('invoice_id')->index();
                $table->foreign('invoice_id')->references('id')->on(CreateInvoicesTable::TABLENAME);
                $table->unsignedInteger('sale_payment_type_id')->index();
                $table->foreign('sale_payment_type_id')->references('id')->on(CreateSalePaymentTypesTable::TABLENAME);
                $table->decimal('amount', 13, 2);
                $table->string('metadata')->default('');
                $table->timestamps();
                $table->softDeletes();
            }
        );

        Schema::table(
            CreateInvoicesTable::TABLENAME,
            function(Blueprint $table)
            {
                $table->dropColumn('taxes');
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

        Schema::table(
            CreateInvoicesTable::TABLENAME,
            function(Blueprint $table)
            {
                $table->decimal('taxes', 13, 2)->default('0.0');
            }
        );
	}
}
