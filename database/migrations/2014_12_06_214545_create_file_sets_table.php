<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileSetsTable extends Migration {

    const TABLENAME = 'file_sets';

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
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->integer('file_category_id')->unsigned()->index();
            $table->foreign('file_category_id')->references('id')->on('file_categories');
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
