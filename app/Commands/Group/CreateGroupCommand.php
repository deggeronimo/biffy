<?php namespace Biffy\Commands\Group;

use Biffy\Commands\AbstractCommand;

class CreateGroupAbstractCommand extends AbstractCommand
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    function __construct($email, $name)
    {
        $this->email = $email;
        $this->name = $name;
    }
} 