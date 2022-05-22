<?php

class Benutzer
{
    private $uuid;

    function __construct($uuid){
        $this->uuid = $uuid;
    }

    // getter
    function getUUID(){
        return $this->uuid;
    }