<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkordersTable extends Migration
{
    const TABLENAME = 'workorders';

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
                $table->text('quickdiag');
                $table->text('itemswithdevice');
                $table->string('notes');
                $table->integer('rating');
                $table->integer('queue')->default(0);
                $table->timestamp('next_update')->index();
                $table->unsignedInteger('device_id')->index();
                $table->foreign('device_id')->references('id')->on(CreateDevicesTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('sale_id')->nullable()->index();
                $table->foreign('sale_id')->references('id')->on(CreateSalesTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('workorder_status_id')->index();
                $table->foreign('workorder_status_id')->references('id')->on(CreateWorkorderStatusesTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('assigned_to_user_id')->nullable()->index();
                $table->foreign('assigned_to_user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('warranty_workorder_id')->nullable()->index();
                $table->foreign('warranty_workorder_id')->references('id')->on(self::TABLENAME)->onDelete('cascade');
                $table->timestamps();
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
        Schema::table(self::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['device_id']);
                $table->dropForeign(['sale_id']);
                $table->dropForeign(['workorder_status_id']);
            });
		Schema::drop(self::TABLENAME);
	}
}
