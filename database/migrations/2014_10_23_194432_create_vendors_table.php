<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVendorsTable extends Migration {

    const TABLENAME = 'vendors';

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
            $table->string('name');
            $table->string('account_number');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->boolean('global')->default(1);
            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on(CreateStoresTable::TABLENAME)->onDelete('cascade');
		});

		Schema::table(
			CreateItemsTable::TABLENAME,
			function (Blueprint $table)
			{
				$table->unsignedInteger('vendor_id')->nullable();
				$table->foreign('vendor_id')->references('id')->on(self::TABLENAME)->onDelete('cascade');
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
			CreateItemsTable::TABLENAME,
			function (Blueprint $table)
			{
				$table->dropForeign(['vendor_id']);
			}
		);

        Schema::table(
			self::TABLENAME,
			function (Blueprint $table)
			{
                $table->dropForeign(['store_id']);
            }
		);

		Schema::drop(self::TABLENAME);
	}

}
