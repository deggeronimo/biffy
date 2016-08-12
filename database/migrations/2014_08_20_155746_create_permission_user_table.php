<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionUserTable extends Migration
{
    const TABLENAME = 'permission_user';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // todo revise onDelete for all foreign keys
        Schema::create(
            self::TABLENAME,
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('permission_id')->unsigned()->index();
                $table->foreign('permission_id')->references('id')->on(CreatePermissionsTable::TABLENAME)->onDelete('cascade');
                $table->integer('user_id')->unsigned()->index();
                $table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
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
        Schema::table(self::TABLENAME, function (Blueprint $table) {
                $table->dropForeign(['permission_id']);
                $table->dropForeign(['user_id']);
            });
        Schema::drop(self::TABLENAME);
    }

}
