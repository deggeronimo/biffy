<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedbackDocsTable extends Migration
{
	const TABLENAME = 'feedback_docs';

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
			$table->string('filename');
			$table->unsignedInteger('store_id')->index();
			$table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
			$table->unsignedInteger('feedback_id')->index()->nullable();
			$table->foreign('feedback_id')->references('id')->on(CreateFeedbacksTable::TABLENAME)->onDelete('cascade');
			$table->unsignedInteger('feedback_doctype_id')->index();
			$table->foreign('feedback_doctype_id')->references('id')->on(CreateFeedbackDoctypesTable::TABLENAME)->onDelete('cascade');
			$table->string('description');
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
