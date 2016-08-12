<?php namespace Biffy\Services\Migrations;

use Illuminate\Database\Migrations\Migrator as IlluminateMigrator;

class Migrator extends IlluminateMigrator
{
    protected function runUp($file, $batch, $pretend)
    {
        $this->disableForeignKeyConstraints();
        parent::runUp($file, $batch, $pretend);
        $this->enableForeignKeyConstraints();
    }

    protected function runDown($migration, $pretend)
    {
        $this->disableForeignKeyConstraints();
        parent::runDown($migration, $pretend);
        $this->enableForeignKeyConstraints();
    }

    private function disableForeignKeyConstraints()
    {
        if (\App::environment() == 'staging') {
            \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        }
    }

    private function enableForeignKeyConstraints()
    {
        if (\App::environment() == 'staging') {
            \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
} 
