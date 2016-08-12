<?php namespace Biffy\Http\Controllers\Helpers;

trait RandomStringGenerator
{
    private function randomString($length = 32)
    {
        static $chars = '0123456789abcdef';

        $retVal = '';
        for ($i = 0; $i < $length; $i ++)
        {
            $retVal = $retVal . $chars[mt_rand(0, 15)];
        }

        return $retVal;
    }
}