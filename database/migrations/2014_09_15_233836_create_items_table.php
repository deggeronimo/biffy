<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsTable extends Migration
{
    const TABLENAME = 'items';

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
				$table->string('item_number');
				$table->decimal('unit_price', 13, 2)->default('0.0');
				$table->decimal('labor_cost', 13, 2)->default('0.0');
				$table->decimal('distro_price', 13, 2)->default('0.0');
				$table->string('name')->index();
				$table->boolean('global')->default(1);
				$table->unsignedInteger('item_type_id')->nullable();
				$table->timestamps();
                $table->unsignedInteger('required')->default(0);
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
