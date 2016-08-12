<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebsiteCategoryTable extends Migration
{
    const TABLENAME = 'website_category';
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
                $table->unsignedInteger('category_id');
                $table->string('image');
                $table->unsignedInteger('parent_id')->nullable()->default(null);
                $table->boolean('top');
                $table->unsignedInteger('product');
                $table->unsignedInteger('column');
                $table->unsignedInteger('sort_order');
                $table->unsignedInteger('status');
                $table->string('model');
                $table->timestamp('date_released');
                $table->timestamp('date_added');
                $table->timestamp('date_modified');
                $table->unsignedInteger('viewed');
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
