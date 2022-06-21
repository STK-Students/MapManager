<?php

class User
{
    private $uuid;

    function __construct($uuid)
    {
        $this->uuid = $uuid;
    }

    function getUUID()
    {
        return $this->uuid;
    }
}