<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {
    const TABLENAME = 'settings';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('key');
                $table->string('type');
                $table->text('extra')->nullable();
                $table->string('default')->nullable();
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
