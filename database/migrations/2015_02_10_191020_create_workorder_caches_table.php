<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkorderCachesTable extends Migration
{
	const TABLENAME = 'workorder_caches';

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
				$table->unsignedInteger('work_order_id')->index();
				$table->foreign('work_order_id')->references('id')->on(CreateWorkordersTable::TABLENAME)->onDelete('cascade');
				$table->integer('needs_to_order_parts')->default(0);
                $table->integer('awaiting_parts')->default(0);
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
