<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyInstructionsTable extends Migration
{
    const TABLENAME = 'company_instructions';

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
                $table->integer('company_id')->unsigned()->index();
                $table->foreign('company_id')->references('id')->on(CreateCompaniesTable::TABLENAME)->onDelete('cascade');
                $table->boolean('lock_trade_credit')->default(true);
                $table->text('email_template')->default('');
                $table->text('display_instructions')->default('');
                $table->timestamps();
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
        Schema::drop(self::TABLENAME);
	}
}
