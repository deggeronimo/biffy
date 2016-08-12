<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportTicketUpdatesTable extends Migration {

    const TABLENAME = 'support_ticket_updates';

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
            $table->integer('author_id')->unsigned()->index();
            $table->foreign('author_id')->references('id')->on('users');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('status_id')->unsigned()->nullable()->index();
            $table->foreign('status_id')->references('id')->on('support_ticket_statuses')->onDelete('set null');
            $table->text('message')->nullable();
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
