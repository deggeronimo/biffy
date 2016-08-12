<?php

namespace Biffy\Commands;

abstract class AbstractCommand
{
    /**
     * @param array $data
     */
    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
} 