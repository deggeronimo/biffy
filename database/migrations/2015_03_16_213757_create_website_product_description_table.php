<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebsiteProductDescriptionTable extends Migration
{
    const TABLENAME = 'website_product_description';

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
                $table->unsignedInteger('language_id');
                $table->string('name');
                $table->text('description');
                $table->string('meta_description');
                $table->string('meta_keyword');
                $table->text('tag');
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
