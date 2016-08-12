<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleItemTaxesTable extends Migration {

    const TABLENAME = 'sale_item_taxes';

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
            $table->unsignedInteger('sale_item_id')->index();
            $table->foreign('sale_item_id')->references('id')->on(CreateSaleItemsTable::TABLENAME)->onDelete('cascade');
            $table->unsignedInteger('store_tax_id')->index();
            $table->foreign('store_tax_id')->references('id')->on(CreateStoreTaxesTable::TABLENAME)->onDelete('cascade');
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
        Schema::table(self::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['sale_item_id']);
                $table->dropForeign(['store_tax_id']);
            });
		Schema::drop(self::TABLENAME);
	}

}
