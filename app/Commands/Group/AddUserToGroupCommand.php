<?php namespace Biffy\Commands\Group;

use Biffy\Commands\AbstractCommand;

class AddUserToGroupAbstractCommand extends AbstractCommand
{
    public $groupEmail;
    public $userEmail;

    function __construct($groupEmail, $userEmail)
    {
        $this->groupEmail = $groupEmail;
        $this->userEmail = $userEmail;
    }
} 