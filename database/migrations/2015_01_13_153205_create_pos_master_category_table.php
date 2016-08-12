<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePosMasterCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pos_master_category', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id')->nullable();
			$table->string('name');
			$table->integer('date_modified');
			$table->integer('parent_id');
			$table->integer('min_item_number');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pos_master_category');
	}

}
