<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedbackCallLogsTable extends Migration {

	const TABLENAME = 'feedback_call_logs';

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
			$table->string('notes');
			$table->string('who_called');
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
