<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRepToUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(CreateUsersTable::TABLENAME, function (Blueprint $table) {
                $table->decimal('rep', 8, 1)->default(0);
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(CreateUsersTable::TABLENAME, function (Blueprint $table) {
                $table->dropColumn('rep');
            });
	}

}
