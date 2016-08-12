<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLanguageStringsTable extends Migration
{
    const TABLENAME = 'language_strings';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create(
            self::TABLENAME,
            function(Blueprint $table)
            {
                $table->increments('id');
                $table->unsignedInteger('language_key_id')->index();
                $table->foreign('language_key_id')->references('id')->on(CreateLanguageKeysTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('language_id')->index();
                $table->foreign('language_id')->references('id')->on(CreateLanguagesTable::TABLENAME)->onDelete('cascade');
                $table->string('string', 1023);

                $table->unique([ 'language_key_id', 'language_id' ]);
            }
        );
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
