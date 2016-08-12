<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiveItemsTable extends Migration
{
    const TABLENAME = 'receive_items';
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
            $table->unsignedInteger('purchase_item_id')->index();
            $table->foreign('purchase_item_id')->references('id')->on(CreatePurchaseItemsTable::TABLENAME)->onDelete('cascade');
            $table->integer('quantity');
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
