<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLanguageForeignToDeviceTypesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table(
            CreateDeviceTypeTable::TABLENAME,
            function(Blueprint $table)
            {
                $table->unsignedInteger('name_language_key_id')->nullable()->default(null);
                $table->foreign('name_language_key_id')->references('id')->on(CreateLanguageKeysTable::TABLENAME);
                $table->unsignedInteger('meta_description_language_key_id')->nullable()->default(null);
                $table->foreign('meta_description_language_key_id')->references('id')->on(CreateLanguageKeysTable::TABLENAME);
                $table->unsignedInteger('meta_keywords_language_key_id')->nullable()->default(null);
                $table->foreign('meta_keywords_language_key_id')->references('id')->on(CreateLanguageKeysTable::TABLENAME);
                $table->unsignedInteger('web_description_language_key_id')->nullable()->default(null);
                $table->foreign('web_description_language_key_id')->references('id')->on(CreateLanguageKeysTable::TABLENAME);
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
            CreateDeviceTypeTable::TABLENAME,
            function(Blueprint $table)
            {
                $table->dropForeign(['name_language_key_id']);
                $table->dropForeign(['meta_description_language_key_id']);
                $table->dropForeign(['meta_keywords_language_key_id']);
                $table->dropForeign(['web_description_language_key_id']);

                $table->dropColumn('name_language_key_id');
                $table->dropColumn('meta_description_language_key_id');
                $table->dropColumn('meta_keywords_language_key_id');
                $table->dropColumn('web_description_language_key_id');
            }
        );
	}
}
