<?php

/**
 * A collection of users that manage a number of @link GeoService s.
 */
class Group
{
    private $uuid;
    private $name;

    function __construct($uuid, $name){
        $this->uuid = $uuid;
        $this->name = $name;
    }

    // getter
    function getUUID(){
        return $this->uuid;
    }
    function getName(){
        return $this->name;
    }

    // setter
    function setName($name){
        $this->name = $name;
    }
}