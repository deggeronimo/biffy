<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardCategoriesTable extends Migration {
    const TABLENAME = 'board_categories';

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
                $table->string('description');
                NestedSet::columns($table);
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
                NestedSet::dropColumns($table);
            });
        Schema::drop(self::TABLENAME);
	}

}
