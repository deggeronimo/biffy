<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedbackNotesTable extends Migration {

	const TABLENAME = 'feedback_notes';

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
			$table->unsignedInteger('feedback_id')->index();
			$table->foreign('feedback_id')->references('id')->on(CreateFeedbacksTable::TABLENAME)->onDelete('cascade');
			$table->unsignedInteger('user_id')->index();
			$table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
			$table->unsignedInteger('feedback_status_id')->index();
			$table->foreign('feedback_status_id')->references('id')->on(CreateFeedbackStatusesTable::TABLENAME)->onDelete('cascade');
			$table->string('notes');
			$table->timestamps();
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
