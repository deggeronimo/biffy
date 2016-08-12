<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration {
    const TABLENAME = 'user_profiles';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLENAME, function(Blueprint $table) {
                $table->unsignedInteger('user_id')->primary();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
                $table->string('phone');
                $table->string('position');
                $table->date('birthday');
                $table->text('about');
                $table->text('signature');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(self::TABLENAME, function(Blueprint $table) {
                $table->dropForeign(['user_id']);
            });

        Schema::drop(self::TABLENAME);
    }

}
