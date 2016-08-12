<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreItemAdjustmentsTable extends Migration
{
    const TABLENAME = 'store_item_adjustments';

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
                $table->unsignedInteger('store_item_id');
                $table->foreign('store_item_id')->references('id')->on(CreateStoreItemTable::TABLENAME);
                $table->unsignedInteger('user_id');
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME);
                $table->string('reason');
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
