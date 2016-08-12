<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseOrdersTable extends Migration {

    const TABLENAME = 'purchase_orders';

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
            $table->unsignedInteger('store_id')->index();
            $table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
			$table->decimal('subtotal', 13, 2)->default('0.0');
			$table->decimal('taxes', 13, 2)->default('0.0');
            $table->decimal('currency_rate', 13, 6);
			$table->decimal('shipping_cost', 13, 2)->default('0.0');
            $table->unsignedInteger('vendor_id')->index();
            $table->foreign('vendor_id')->references('id')->on(CreateVendorsTable::TABLENAME)->onDelete('cascade');
            $table->string('tracking_number');
            $table->boolean('finalized')->default('0');
            $table->string('shipping_method')->default('UPS Ground');
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
