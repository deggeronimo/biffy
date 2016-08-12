<?php namespace Biffy\Entities;

use Kalnoy\Nestedset\Node;

abstract class AbstractNode extends Node
{
    public function getStrings()
    {
        return [];
    }
} 