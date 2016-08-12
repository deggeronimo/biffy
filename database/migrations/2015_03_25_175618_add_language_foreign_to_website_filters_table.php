<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLanguageForeignToWebsiteFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            CreateWebsiteFiltersTable::TABLENAME,
            function(Blueprint $table)
            {
                $table->unsignedInteger('name_language_key_id')->nullable()->default(null);
                $table->foreign('name_language_key_id')->references('id')->on(CreateLanguageKeysTable::TABLENAME);
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
            CreateWebsiteFiltersTable::TABLENAME,
            function(Blueprint $table)
            {
                $table->dropForeign(['name_language_key_id']);

                $table->dropColumn('name_language_key_id');
            }
        );
    }
}
