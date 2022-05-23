<?php

class Map
{
    private $uuid;
    private $name;
    private $description;
    private $creationDate;
    private $groupUUID;


    function __construct($uuid, $name, $description, $creationDate, $groupUUID) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->creationDate = $creationDate;
        $this->groupUUID = $groupUUID;
    }

    // getter
    function getUUID(){
        return $this->uuid;
    }

    function getName(){
        return $this->name;
    }

    function getDescription(){
        return $this->description;
    }

    function getCreationDate(){
        return $this->creationDate;
    }

    function getGroupUUID(){
        return $this->groupUUID;
    }

    // setter
    function setName($name){
        $this->name = $name;
    }

    function setDescription($description){
        $this->description = $description;
    }

    function setCreation($creationDate){
        $this->creationDate = $creationDate;
    }

}