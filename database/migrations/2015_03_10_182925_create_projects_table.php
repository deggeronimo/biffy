<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {
    const TABLENAME = 'projects';

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
                $table->boolean('closed');
                $table->timestamp('completed_at')->nullable();
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
