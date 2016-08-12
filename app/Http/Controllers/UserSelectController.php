<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\User\UserService;

/**
 * Class UserSelectController
 * @package Biffy\Http\Controllers
 */
class UserSelectController extends ApiController
{
    /**
     * @var UserService
     */
    public $service;

    /**
     * @param UserService $userService
     */
    function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    function stores($userId)
    {
        return $this->data($this->service->userStores($userId))->respond();
    }
}