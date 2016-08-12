<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTable extends Migration {
    const TABLENAME = 'user_settings';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('setting_id')->index();
                $table->foreign('setting_id')->references('id')->on(CreateSettingsTable::TABLENAME)->onDelete('cascade');
                $table->string('value')->nullable();
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
		Schema::table(self::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['setting_id']);
            });
        Schema::drop(self::TABLENAME);
	}

}
