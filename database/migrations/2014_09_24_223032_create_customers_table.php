<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration {

    const TABLENAME = 'customers';

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
            $table->string('given_name');
            $table->string('family_name');
            $table->string('full_name')->index();
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('state')->nullable();
            $table->string('postal_code');
            $table->string('country')->nullable();
            $table->string('referral_source')->nullable();
            $table->integer('store_id')->unsigned()->index();
            $table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
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
