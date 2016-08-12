<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSupportTicketWatcherTable extends Migration {

    const TABLENAME = 'support_ticket_watcher';
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
			$table->integer('support_ticket_id')->unsigned()->index();
			$table->foreign('support_ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
