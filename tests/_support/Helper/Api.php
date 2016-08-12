<?php
namespace Helper;
// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends \Codeception\Module
{
    public function _beforeSuite($settings = [])
    {
        $path = \Config::get('database.connections.testing.database');
        copy(storage_path('testing-setup.sqlite'), $path);
    }

    public function _afterSuite()
    {
        $path = \Config::get('database.connections.testing.database');
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
