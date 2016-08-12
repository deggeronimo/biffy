<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration {

    const TABLENAME = 'leads';

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
            $table->string('given_name')->index();
            $table->string('family_name')->index();
            $table->string('phone')->index();
            $table->string('email')->index();
            $table->string('postal_code')-> index();
            $table->string('device')->index();
            $table->longText('issue')->nullable();
            $table->float('price')->nullable();
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
