<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTasksTable extends Migration {
    const TABLENAME = 'project_tasks';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(self::TABLENAME, function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('project_id')->index()->nullable();
                $table->foreign('project_id')->references('id')->on(CreateProjectsTable::TABLENAME)->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->unsignedInteger('parent')->index()->nullable();
                $table->foreign('parent')->references('id')->on(self::TABLENAME)->onDelete('cascade');
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
                $table->dropForeign(['project_id']);
            });
        Schema::drop(self::TABLENAME);
	}

}
