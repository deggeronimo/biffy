<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesTable extends Migration
{
    const TABLENAME = 'sales';

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
                $table->boolean('completed')->default(false);
                $table->decimal('subtotal', 13, 2)->default(0.0);
                $table->decimal('taxes', 13, 2)->default(0.0);
                $table->decimal('payments', 13, 2)->default(0.0);
                $table->decimal('trade_credit', 13, 2)->default(0.0);
                $table->decimal('adjustments', 13, 2)->default(0.0);
                $table->unsignedInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('store_id')->index();
                $table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('customer_id')->index()->nullable();
                $table->foreign('customer_id')->references('id')->on(CreateCustomersTable::TABLENAME)->onDelete('cascade');
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
        Schema::table(self::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['store_id']);
                $table->dropForeign(['customer_id']);
            });

		Schema::drop(self::TABLENAME);
	}

}
