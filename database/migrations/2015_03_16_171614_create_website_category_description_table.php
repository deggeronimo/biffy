<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebsiteCategoryDescriptionTable extends Migration
{
    const TABLENAME = 'website_category_description';

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
                $table->unsignedInteger('language_id');
                $table->string('name');
                $table->string('description');
                $table->string('meta_description');
                $table->string('meta_keyword');
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
