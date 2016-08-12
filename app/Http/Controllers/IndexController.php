<?php namespace Biffy\Http\Controllers;

use Biffy\Exceptions\InvalidAuthEmailException;

/**
 * Class IndexController
 * @package Biffy\Http\Controllers
 */
class IndexController extends BaseController
{
    private $loginConstant = "<script>angular.module('biffyApp').constant('authLoginPage', BOOLEAN);</script>";

    /**
     * @return mixed
     */
    public function client()
    {
        try {
            \Auth::pinLogout();
            if (!\Auth::check()) {
                $this->setLoginConstant(true);
            } else {
                $this->setLoginConstant(false);
            }
        } catch (InvalidAuthEmailException $e) {
            $this->setLoginConstant(false);
        }

        if( file_exists( public_path('_index.html') ) )
        {
            echo str_replace('<!--LOGIN_INIT-->', $this->loginConstant, file_get_contents(public_path('_index.html')));
            exit;
        } else {
            return \Response::json(['messages' => ['error' => ['Action could not be completed due to missing route']]], 404);
        }
    }

    private function setLoginConstant($value)
    {
        $this->loginConstant = str_replace('BOOLEAN', $value ? 'true' : 'false', $this->loginConstant);
    }

}
