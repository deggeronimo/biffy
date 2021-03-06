<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_contacts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on(CreateCustomersTable::TABLENAME)->onDelete('cascade');
            $table->string('phone');
            $table->string('type');
            $table->string('direction');
            $table->string('status');
            $table->longText('content');
            $table->integer('duration');
            $table->dateTime('date');
            $table->string('callid');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customer_contacts');
	}

}
