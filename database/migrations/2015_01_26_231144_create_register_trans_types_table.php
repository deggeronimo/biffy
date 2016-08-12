<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegisterTransTypesTable extends Migration
{
	const TABLENAME = 'register_trans_types';

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
			CreateRegisterTransRecordsTable::TABLENAME,
			function (Blueprint $table)
			{
				$table->integer('register_trans_type_id')->unsigned()->index()->default(1);
				$table->foreign('register_trans_type_id')->references('id')->on(self::TABLENAME)->onDelete('cascade');
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
                $table->dropForeign(['register_trans_type_id']);
            }
        );

        Schema::drop(self::TABLENAME);
	}
}
