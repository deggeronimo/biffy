<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceItemChecklistsTable extends Migration {

    const TABLENAME = 'device_item_checklists';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('device_type_id')->index();
            $table->foreign('device_type_id')->references('id')->on(CreateDeviceTypeTable::TABLENAME)->onDelete('cascade');
            $table->unsignedInteger('checklist_item_id')->index();
            $table->foreign('checklist_item_id')->references('id')->on(CreateChecklistItemsTable::TABLENAME)->onDelete('cascade');
		});
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
