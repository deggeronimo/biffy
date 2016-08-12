<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebsiteProductTable extends Migration
{
    const TABLENAME = 'website_product';

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
                $table->unsignedInteger('product_id');
                $table->string('model');
                $table->string('sku');
                $table->string('upc');
                $table->string('ean');
                $table->string('jan');
                $table->string('isbn');
                $table->string('mpn');
                $table->string('location');
                $table->string('pos_type');
                $table->integer('quantity');
                $table->unsignedInteger('stock_status_id');
                $table->string('image')->nullable()->default(null);
                $table->unsignedInteger('manufacturer_id');
                $table->boolean('shipping')->default(true);
                $table->decimal('price', 15, 4)->default(0.0);
                $table->integer('points');
                $table->unsignedInteger('tax_class_id');
                $table->timestamp('date_available');
                $table->decimal('weight', 15, 8)->default(0.0);
                $table->unsignedInteger('weight_class_id');
                $table->decimal('length', 15, 8)->default(0.0);
                $table->decimal('width', 15, 8)->default(0.0);
                $table->decimal('height', 15, 8)->default(0.0);
                $table->unsignedInteger('length_class_id');
                $table->boolean('subtract')->default(true);
                $table->integer('minimum')->default(1);
                $table->integer('sort_order')->default(1);
                $table->boolean('status')->default(true);
                $table->timestamp('date_added');
                $table->timestamp('date_modified');
                $table->unsignedInteger('viewed');
                $table->integer('part_id');
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
