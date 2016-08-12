<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkorderNotesTable extends Migration
{
    const TABLENAME = 'workorder_notes';

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
                $table->boolean('public')->default(false);
                $table->unsignedInteger('work_order_id')->index();
                $table->foreign('work_order_id')->references('id')->on(CreateWorkordersTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('workorder_status_id')->index();
                $table->foreign('workorder_status_id')->references('id')->on(CreateWorkorderStatusesTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('action_id')->nullable()->index();
                $table->foreign('action_id')->references('id')->on(CreateActionsTable::TABLENAME)->onDelete('cascade');
                $table->timestamp('next_update_time');
                $table->string('notes');
                $table->boolean('auto')->default(false);
                $table->text('diag')->nullable();
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
        Schema::table(
            self::TABLENAME,
            function (Blueprint $table)
            {
                $table->dropForeign(['work_order_id']);
                $table->dropForeign(['user_id']);
                $table->dropForeign(['workorder_status_id']);
                $table->dropForeign(['action_id']);
            }
        );

		Schema::drop(self::TABLENAME);
	}
}
