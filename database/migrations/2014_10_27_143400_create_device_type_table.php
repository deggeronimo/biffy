<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceTypeTable extends Migration
{
    const TABLENAME = 'device_types';

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
                $table->boolean('selectable')->default(0);
                $table->unsignedInteger('parent_device_type_id')->nullable()->index();
                $table->foreign('parent_device_type_id')->references('id')->on(self::TABLENAME)->onDelete('cascade');
                $table->softDeletes();
                $table->timestamps();
            }
        );

		Schema::table(
            CreateItemsTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->integer('device_type_id')->unsigned()->index()->nullable();
                $table->foreign('device_type_id')->references('id')->on(CreateDeviceTypeTable::TABLENAME)->onDelete('cascade');
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
            function (Blueprint $table)
            {
                $table->dropForeign(['device_type_id']);
            }
        );

		Schema::drop(self::TABLENAME);
	}
}
