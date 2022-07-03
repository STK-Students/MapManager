<?php

class User
{
    private $uuid;
    private $firstname;
    private $lastname;
    private $username;
    private $password;

    function __construct($uuid, $firstname, $lastname, $username, $password)
    {
        $this->uuid = $uuid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->password = $password;
    }

    function getUUID()
    {
        return $this->uuid;
    }
    function getFirstname()
    {
        return $this->firstname;
    }
    function getLastname()
    {
        return $this->lastname;
    }
    function getUsername()
    {
        return $this->username;
    }
}