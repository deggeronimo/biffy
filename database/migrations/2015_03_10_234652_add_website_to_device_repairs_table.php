<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddWebsiteToDeviceRepairsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table(
            CreateDeviceRepairsTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->string('image')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedInteger('view_count')->default(0);
                $table->boolean('status')->default(true);
                $table->string('tags')->default('');
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
            CreateDeviceRepairsTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->dropColumn('image');
                $table->dropColumn('sort_order');
                $table->dropColumn('view_count');
                $table->dropColumn('status');
                $table->dropColumn('tags');
            }
        );
	}
}
