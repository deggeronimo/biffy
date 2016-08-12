<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyStoreItemsTable extends Migration
{
    const TABLENAME = 'company_store_items';

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
                $table->integer('company_id')->unsigned()->index();
                $table->foreign('company_id')->references('id')->on(CreateCompaniesTable::TABLENAME)->onDelete('cascade');
                $table->integer('store_item_id')->unsigned()->index();
                $table->foreign('store_item_id')->references('id')->on(CreateStoreItemTable::TABLENAME)->onDelete('cascade');
                $table->decimal('unit_price', 13, 2)->default('0.0');
                $table->decimal('labor_cost', 13, 2)->default('0.0');
                $table->timestamps();

                $table->unique([ 'company_id', 'store_item_id' ]);
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
