<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyContactsTable extends Migration {

    const TABLENAME = 'company_contacts';

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
            $table->unsignedInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on(CreateCompaniesTable::TABLENAME)->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
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
