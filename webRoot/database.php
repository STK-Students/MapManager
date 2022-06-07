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
        $result = pg_query($this->db_connection, "Select * From public.group");
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $item = new Group($line['uuid'], $line['name']);
            array_push($groups, $item);
        }
        return $groups;
    }

    function getGroup($groupUUID)
    {
        $result = pg_query($this->db_connection,"Select * From public.group");
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            if($line['uuid'] == $groupUUID){
                return new Group($line['uuid'], $line['name']);
            }
        }
    }
    function getMap($mapUUID)
    {
        $result = pg_query($this->db_connection,"Select * From public.map");
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            if($line['uuid'] == $mapUUID){
                return new Map($line['uuid'], $line['name'], $line['description'], $line['creationDate'], $line['groupUUID']);
            }
        }
    }

    function getUsers()
    {
        $users = array();
        $result = pg_query($this->db_connection,"Select * From public.user");
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $item = new User($line['uuid']);
            array_push($users, $item);
        }
        return $users;
    }

    function getMaps($groupUUID)
    {
        $maps = array();
        $result = pg_query_params($this->db_connection, 'SELECT * FROM public.map WHERE "groupUUID"=$1', Array($groupUUID));
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $item = new Map($line['uuid'], $line['name'], $line['description'], $line['creationDate'], $line['groupUUID']);
            $maps[] = $item;
        }
        return $maps;
    }

    function addGroup($name)
    {
        return pg_query_params($this->db_connection, 'INSERT INTO public.group (name) VALUES ($1)', Array($name));
    }

    function removeGroup($groupUUID) {
        $result = pg_query_params($this->db_connection, 'DELETE From public.group WHERE uuid=$1', Array($groupUUID));
        return $result;
    }

    function removeMap($mapUUID) {
        $result = pg_query_params($this->db_connection, 'DELETE From public.map WHERE uuid=$1', Array($mapUUID));
        return $result;
    }

    function addMap($name, $description, $creationDate, $groupUUID)
    {
        return pg_query_params($this->db_connection, 'INSERT INTO public.map (name, description, "creationDate", "groupUUID") VALUES ($1, $2, $3, $4)', Array($name, $description, $creationDate, $groupUUID));
    }

    function editGroup($groupUUID, $name){
        return pg_query_params($this->db_connection, 'UPDATE public.group SET name=$2 WHERE uuid=$1', Array($groupUUID, $name));
    }

    function editMap($mapUUID, $name, $description){
        return pg_query_params($this->db_connection, 'UPDATE public.map SET name=$2, description=$3 WHERE uuid=$1', Array($mapUUID, $name, $description));
    }

    function changeGroupOfMap($mapUUID, $groupUUID){
        return pg_query_params($this->db_connection, 'UPDATE public.map SET groupUUID=$2 WHERE uuid=$1', Array($mapUUID, $groupUUID));
    }

    function addUserToGroup($groupUUID, $userUUID){
        return pg_query_params($this->db_connection, 'INSERT INTO public.rel_user_group (group_uuid, user_LDAP_UUID) VALUES ($1, $2)', Array($groupUUID, $userUUID));
    }

    function removeUserFromGroup($groupUUID, $userUUID){
        return pg_query_params($this->db_connection, 'DELETE FROM public.rel_user_group WHERE group_uuid=$1 and user_LDAP_UUID=$2', Array($groupUUID, $userUUID));
    }
}