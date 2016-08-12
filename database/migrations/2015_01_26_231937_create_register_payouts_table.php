<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegisterPayoutsTable extends Migration
{
	const TABLENAME = 'register_payouts';

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
				$table->decimal('amount', 13, 2);
				$table->unsignedInteger('supplier_id');
				$table->foreign('supplier_id')->references('id')->on(CreateSuppliersTable::TABLENAME)->onDelete('cascade');
				$table->unsignedInteger('store_id');
				$table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
				$table->unsignedInteger('user_id');
				$table->foreign('user_id')->references('id')->on(CreateUsersTable::TABLENAME)->onDelete('cascade');
				$table->string('filename');
				$table->string('comments');
				$table->timestamps();
			}
		);

		Schema::table(
			CreateRegisterTransRecordsTable::TABLENAME,
			function (Blueprint $table)
			{
				$table->integer('register_payout_id')->unsigned()->index()->nullable();
				$table->foreign('register_payout_id')->references('id')->on(self::TABLENAME)->onDelete('cascade');
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
            CreateRegisterTransRecordsTable::TABLENAME,
            function (Blueprint $table)
            {
                $table->dropForeign(['register_payout_id']);
            }
        );

		Schema::drop(self::TABLENAME);
	}
}
