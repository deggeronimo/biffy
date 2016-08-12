<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleItemsTable extends Migration
{
    const TABLENAME = 'sale_items';

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
                $table->unsignedInteger('sale_id')->nullable()->index();
                $table->foreign('sale_id')->references('id')->on(CreateSalesTable::TABLENAME);
                $table->unsignedInteger('inventory_id')->index();
                $table->foreign('inventory_id')->references('id')->on(CreateInventoryTable::TABLENAME);
                $table->decimal('price', 13, 2)->default('0.0');
                $table->decimal('labor_cost', 13, 2)->default('0.0');
                $table->decimal('discount', 13, 8)->default('0.0');
                $table->boolean('on_receipt')->default('1');
                $table->boolean('tax_exempt')->default('0');
                $table->string('name');
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
        Schema::table(
            self::TABLENAME,
            function (Blueprint $table)
            {
                $table->dropForeign(['sale_id']);
                $table->dropForeign(['inventory_id']);
            }
        );

        Schema::drop(self::TABLENAME);
	}

}
