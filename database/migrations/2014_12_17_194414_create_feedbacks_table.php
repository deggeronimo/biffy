<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedbacksTable extends Migration {

	const TABLENAME = 'feedbacks';
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
			$table->unsignedInteger('feedback_status_id')->index();
			$table->foreign('feedback_status_id')->references('id')->on(CreateFeedbackStatusesTable::TABLENAME)->onDelete('cascade');
			$table->unsignedInteger('assigned_to_user_id')->index();
			$table->foreign('assigned_to_user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');

			$table->unsignedInteger('customer_id')->nullable()->index();
			$table->foreign('customer_id')->references('id')->on(CreateCustomersTable::TABLENAME)->onDelete('cascade');
			$table->unsignedInteger('sale_id')->index();
			$table->foreign('sale_id')->references('id')->on(CreateSalesTable::TABLENAME)->onDelete('cascade');
			$table->timestamp('visit_time')->index();

			$table->integer('recommend_rating');
			$table->integer('status_aware_rating');
			$table->integer('repair_on_time_rating');
			$table->integer('friendly_rating');
			$table->integer('communication_rating');
			$table->integer('overall_rating');

			$table->text('main_reason');
			$table->text('best_part');
			$table->text('we_improve');
			$table->text('more_comfortable');
			$table->text('why_choose_score');

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
