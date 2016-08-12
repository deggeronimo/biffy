<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportTicketsTable extends Migration {

    const TABLENAME = 'support_tickets';

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
            $table->integer('author_id')->unsigned()->index();
            $table->foreign('author_id')->references('id')->on('users');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('status_id')->unsigned()->nullable()->index();
            $table->foreign('status_id')->references('id')->on('support_ticket_statuses')->onDelete('set null');
            $table->integer('category_id')->unsigned()->nullable()->index();
            $table->foreign('category_id')->references('id')->on('support_ticket_categories')->onDelete('set null');
            $table->string('title')->index();
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
