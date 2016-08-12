<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanySaleApprovalsTable extends Migration
{
    const TABLENAME = 'company_sale_approvals';

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
                $table->unsignedInteger('sale_id')->nullable()->index();
                $table->foreign('sale_id')->references('id')->on(CreateSalesTable::TABLENAME)->onDelete('cascade');
                $table->unsignedInteger('workorder_id')->nullable()->index();
                $table->foreign('workorder_id')->references('id')->on(CreateWorkordersTable::TABLENAME)->onDelete('cascade');
                $table->string('approval_code')->nullable()->default(null);
                $table->boolean('approved')->default(false);
                $table->timestamps();

                $table->unique([ 'sale_id' ]);
                $table->unique([ 'workorder_id' ]);
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
