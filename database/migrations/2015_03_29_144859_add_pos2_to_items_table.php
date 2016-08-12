<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPos2ToItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table(
            CreateItemsTable::TABLENAME,
            function(Blueprint $table)
            {
                $table->unsignedInteger('old_item_id')->nullable();
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
        Schema::table(
            CreateItemsTable::TABLENAME,
            function(Blueprint $table)
            {
                $table->dropColumn('old_item_id');
            }
        );
	}
}
