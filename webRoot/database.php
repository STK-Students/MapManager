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

    function addGroup($name)
    {
        $result = pg_query_params($this->db_connection, 'INSERT INTO public.group (name) VALUES ($1)', Array($name));
        return $result;
    }

    function addMap($name, $description, $creationDate, $groupUUID)
    {
        $result = pg_query_params($this->db_connection, 'INSERT INTO public.group (name, description, creationDate, groupUUID) VALUES ($1, $2, $3, $4)', Array($name, $description, $creationDate, $groupUUID));
        return $result;
    }

    function editGroup($groupUUID, $name){
        $result = pg_query_params($this->db_connection, 'UPDATE public.group SET name=$2 WHERE uuid=$1', Array($groupUUID, $name));
        return $result;
    }

    function editMap($mapUUID, $name, $description, $creationDate){
        $result = pg_query_params($this->db_connection, 'UPDATE public.map SET name=$2, description=$3, creationDate=$4 WHERE uuid=$1', Array($mapUUID, $name, $description, $creationDate));
        return $result;
    }

    function changeGroupOfMap($mapUUID, $groupUUID){
        $result = pg_query_params($this->db_connection, 'UPDATE public.map SET groupUUID=$2 WHERE uuid=$1', Array($mapUUID, $groupUUID));
        return $result;
    }

    function addUserToGroup($groupUUID, $userUUID){
        $result = pg_query_params($this->db_connection, 'INSERT INTO public.rel_user_group (group_uuid, user_LDAP_UUID) VALUES ($1, $2)', Array($groupUUID, $userUUID));
        return $result;
    }

    function removeUserFromGroup($groupUUID, $userUUID){
        $result = pg_query_params($this->db_connection, 'DELETE FROM public.rel_user_group WHERE group_uuid=$1 and user_LDAP_UUID=$2', Array($groupUUID, $userUUID));
        return $result;
    }
}