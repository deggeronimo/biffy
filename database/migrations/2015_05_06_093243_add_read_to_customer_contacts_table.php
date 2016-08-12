<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadToCustomerContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_contacts', function(Blueprint $table)
		{
            $table->boolean('sms_read')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_contacts', function(Blueprint $table)
		{
			$table->dropColumn('sms_read');
		});
	}

}
