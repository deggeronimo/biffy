<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration {

    const TABLENAME = 'files';

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
            $table->string('path')->index();
            $table->integer('file_set_id')->unsigned()->index();
            $table->foreign('file_set_id')->references('id')->on('file_sets');
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
