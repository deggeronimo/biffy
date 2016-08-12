<?php

class BaseSeeder extends \Illuminate\Database\Seeder
{
    protected $truncateTables = [];

    protected function cleanDatabase()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($this->truncateTables as $tableName)
        {
            DB::table($tableName)->truncate();
        }
    }
} 