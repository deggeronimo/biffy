<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkorderStatusesTable extends Migration
{
    const TABLENAME = 'workorder_statuses';

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
                $table->boolean('initial')->default(false);
				$table->string('next_time')->nullable()->default(null);
                $table->string('action_text_key')->default('');
                $table->boolean('user_can_set')->default(true);
                $table->boolean('remove_items')->default(false);
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
