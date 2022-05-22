<?php

class Karte
{
    private $uuid;
    private $name;
    private $description;
    private $creationDate;


    function __construct($uuid, $name, $description, $creationDate) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->creationDate = $creationDate;
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