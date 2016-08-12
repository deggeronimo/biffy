<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePosMasterInventoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pos_master_inventory', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('category');
			$table->integer('category_id');
			$table->integer('supplier_id')->nullable();
			$table->string('item_number');
			$table->string('description');
			$table->decimal('unit_price', 13, 2);
			$table->decimal('promo_price', 13, 2);
			$table->dateTime('start_date');
			$table->dateTime('end_date');
			$table->decimal('reorder_level', 13, 2);
			$table->decimal('recommended_reorder_level', 13, 2);
			$table->integer('tax_id');
			$table->string('location');
			$table->integer('item_id');
			$table->integer('allow_alt_description');
			$table->integer('is_serialized');
			$table->integer('deleted');
			$table->string('item_type');
			$table->integer('in_distro');
			$table->integer('in_store');
			$table->dateTime('date_modified');
			$table->decimal('labor_cost', 13, 2);
			$table->integer('labor_auto_split');
			$table->string('added_by')->nullable();
			$table->integer('recent_sold')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pos_master_inventory');
	}

}
