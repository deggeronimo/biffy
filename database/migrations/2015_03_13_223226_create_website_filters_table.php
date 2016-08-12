<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebsiteFiltersTable extends Migration
{
    const TABLENAME = 'website_filters';

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
                $table->unsignedInteger('filter_group_id');
                $table->foreign('filter_group_id')->references('id')->on(CreateWebsiteFilterGroupsTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedInteger('portal_filter_id');
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
