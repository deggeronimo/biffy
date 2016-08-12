<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInventoryTable extends Migration
{
    const TABLENAME = 'inventory';

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
				$table->integer('store_item_id')->unsigned()->index();
				$table->foreign('store_item_id')->references('id')->on(CreateStoreItemTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('purchased_by_user_id')->nullable()->index();
                $table->foreign('purchased_by_user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('received_by_user_id')->nullable()->index();
                $table->foreign('received_by_user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
				$table->unsignedInteger('sold_by_user_id')->nullable()->index();
				$table->foreign('sold_by_user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
				$table->decimal('cost', 13, 2)->default('0.0');
                $table->decimal('shipping_cost', 13, 4)->default('0.0');
				$table->integer('status')->default(1);
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
                $table->dropForeign(['store_item_id']);
            });
		Schema::drop(self::TABLENAME);
	}
}
