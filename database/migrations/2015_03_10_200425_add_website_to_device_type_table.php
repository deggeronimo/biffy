<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddWebsiteToDeviceTypeTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table(
            CreateDeviceTypeTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->string('image')->nullable();
                $table->boolean('top')->default(0);
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('product')->default(0);
                $table->boolean('status')->default(true);
                $table->string('model')->default('');
                $table->unsignedInteger('view_count')->default(0);
                $table->date('release_date')->default('0000-00-00');
                $table->string('filters')->default('');
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
            CreateDeviceTypeTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->dropColumn('image');
                $table->dropColumn('top');
                $table->dropColumn('sort_order');
                $table->dropColumn('status');
                $table->dropColumn('model');
                $table->dropColumn('view_count');
                $table->dropColumn('release_date');
                $table->dropColumn('filters');
            }
        );
	}
}
