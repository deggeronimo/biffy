<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    const TABLENAME = 'companies';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            self::TABLENAME,
            function(Blueprint $table)
            {
                $table->increments('id');
                $table->string('name')->index();
                $table->string('address_line_1');
                $table->string('address_line_2')->nullable();
                $table->string('phone')->index();
                $table->string('email')->index();
                $table->decimal('discount', 13, 2)->nullable();
                $table->timestamps();
            }
        );

        Schema::table(
            CreateSalesTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->unsignedInteger('company_id')->index()->nullable();
                $table->foreign('company_id')->references('id')->on(CreateCompaniesTable::TABLENAME)->onDelete('cascade');
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
        Schema::table(
            CreateSalesTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->dropForeign(['company_id']);
            }
        );

        Schema::drop(self::TABLENAME);
    }
}
