<?php

require("model/user.php");
require("model/group.php");
require("model/map.php");


class Database
{
    private $db_connection;
    private $hostname;
    private $db;
    private $user;
    private $password;

    function __construct($hostname, $db, $user, $password)
    {
        $this->hostname = $hostname;
        $this->db = $db;
        $this->user = $user;
        $this->password = $password;
        $this->db_connection = pg_connect("host=$this->hostname dbname=$this->db user=$this->user password=$this->password") or die("Verbindungsaufbau fehlgeschlagen");
    }

    function getGroups()
    {
        $groups = array();
        $result = pg_query("Select * From public.group");
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            var_dump($line);
            $item = new Group($line['uuid'], $line['name']);
            array_push($groups, $item);
        }
        return $groups;
    }

    function getUsers()
    {
        $users = array();
        $result = pg_query("Select * From public.user");
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $item = new User($line['uuid']);
            array_push($users, $item);
        }
        return $users;
    }

    function getMaps()
    {
        $maps = array();
        $result = pg_query("Select * From public.map");
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $item = new Map($line['uuid'], $line['name'], $line['description'], $line['creationDate'], $line['groupUUID']);
            array_push($maps, $item);
        }
        return $maps;
    }

    function writeGroup()
    {

    }

    function writeUsers()
    {

    }

    function writeMap()
    {

    }

}