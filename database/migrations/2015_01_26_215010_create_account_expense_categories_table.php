<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountExpenseCategoriesTable extends Migration
{
	const TABLENAME = 'account_expense_categories';

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
				$table->string('name');
			}
		);

		Schema::table(
			CreateAccountExpensesTable::TABLENAME,
			function (Blueprint $table)
			{
				$table->integer('account_expense_category_id')->unsigned()->index()->default(1);
				$table->foreign('account_expense_category_id')->references('id')->on(self::TABLENAME)->onDelete('cascade');
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
            CreateAccountExpensesTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->dropForeign(['account_expense_category_id']);
            }
        );

		Schema::drop(self::TABLENAME);
	}
}
