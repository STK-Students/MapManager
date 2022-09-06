<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/Config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/model/User.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/model/Group.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/model/GeoService.php";

/**
 * A Singleton Database class.
 * Use @see Database::getInstance() to obtain the object's instance.
 */
class Database
{
    private static ?Database $instance = null;
    private $db_connection;
    private $schema;

    /**
     * Gets the Database instance, ready to execute queries.
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self(Config::getConfig());
        }

        return self::$instance;
    }

    private function __construct($config)
    {
        $dbData = $config['postgres'];
        $this->schema = $dbData['schema'];
        $this->db_connection = pg_connect("host={$dbData['hostname']} dbname={$dbData['database']} user={$dbData['user']} password={$dbData['password']}") or die("Verbindungsaufbau fehlgeschlagen");
    }

    function getGroups()
    {
        $groups = array();
        $result = pg_query($this->db_connection, "Select * From {$this->schema}.group");
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $item = new Group($line['uuid'], $line['name']);
            array_push($groups, $item);
        }
        return $groups;
    }

    function getGroupsFromUser($user_uuid)
    {
        $groups = array();
        $result = pg_query_params($this->db_connection, "Select {$this->schema}.group.uuid, {$this->schema}.group.name From {$this->schema}.group, {$this->schema}.rel_user_group Where {$this->schema}.rel_user_group.user_ad_id = $1 And {$this->schema}.rel_user_group.group_uuid={$this->schema}.group.uuid", array($user_uuid));
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $item = new Group($line['uuid'], $line['name']);
            array_push($groups, $item);
        }
        return $groups;
    }

    function getGroup($groupUUID)
    {
        $result = pg_query($this->db_connection, "Select * From {$this->schema}.group");
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            if ($line['uuid'] == $groupUUID) {
                return new Group($line['uuid'], $line['name']);
            }
        }
    }

    function getGeoService($mapUUID): GeoService
    {
        $result = pg_query_params($this->db_connection, "Select * From {$this->schema}.map WHERE uuid=$1", array($mapUUID));
        $row = pg_fetch_array($result, null, PGSQL_ASSOC);
        return new GeoService($row['uuid'], $row['name'], $row['description'], $row['creationDate'], $row['groupUUID']);
    }

    function addUser($adID)
    {
        pg_query_params($this->db_connection, "INSERT INTO {$this->schema}.user(ad_id) VALUES ($1) On CONFLICT(ad_id) DO NOTHING;", array($adID));
    }

    function getUser($user_uuid)
    {
        $result = pg_query_params($this->db_connection, "Select * From {$this->schema}.user Where ad_id=$1", array($user_uuid));
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $item = new User($line['ad_id'], $line['firstname'], $line['lastname'], $line['username'], $line['password']);
            return $item;
        }
        return null;
    }

    function getUsersFromGroup($group_uuid)
    {
        $users = array();
        $result = pg_query_params($this->db_connection, "Select * From {$this->schema}.rel_user_group Where group_uuid=$1", array($group_uuid));
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $user = (object)$this->getUser($line['user_ad_id']);
            $users[] = $user;
        }
        return $users;
    }

    function isUserInGroup($user_uuid, $group_uuid)
    {
        $result = pg_query_params($this->db_connection, "Select * From {$this->schema}.rel_user_group Where group_uuid=$1 And user_ad_id=$2", array($group_uuid, $user_uuid));
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            if ($line == null) {
                return false;
            } else {
                return true;
            }
        }
    }

    function getGeoServices($groupUUID): array
    {
        $geoServices = array();
        $result = pg_query_params($this->db_connection, "SELECT * FROM {$this->schema}.map WHERE \"groupUUID\"=$1", array($groupUUID));
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $geoServices[] = new GeoService($line['uuid'], $line['name'], $line['description'], $line['creationDate'], $line['groupUUID']);
        }
        return $geoServices;
    }

    function addGroup($name, $uuid)
    {
        pg_query_params($this->db_connection, "INSERT INTO {$this->schema}.group(uuid, name) VALUES ($1, $2) On CONFLICT(uuid) DO NOTHING;", array($uuid, $name));
    }

    function removeGroup($groupUUID)
    {
        return pg_query_params($this->db_connection, "DELETE From {$this->schema}.group WHERE uuid=$1", array($groupUUID));
    }

    function removeUsersFromGroup($groupUUID)
    {
        return pg_query_params($this->db_connection, "DELETE From {$this->schema}.rel_user_group WHERE group_uuid=$1", array($groupUUID));
    }

    function removeMap($mapUUID)
    {
        return pg_query_params($this->db_connection, "DELETE From {$this->schema}.map WHERE uuid=$1", array($mapUUID));
    }

    function addMap($name, $description, $creationDate, $groupUUID)
    {
        return pg_query_params($this->db_connection, "INSERT INTO {$this->schema}.map (name, description, \"creationDate\", \"groupUUID\") VALUES ($1, $2, $3, $4) RETURNING uuid", array($name, $description, $creationDate, $groupUUID));
    }

    function editGroup($groupUUID, $name)
    {
        return pg_query_params($this->db_connection, "UPDATE {$this->schema}.group SET name=$2 WHERE uuid=$1", array($groupUUID, $name));
    }

    function editMap($mapUUID, $name, $description)
    {
        return pg_query_params($this->db_connection, "UPDATE {$this->schema}.map SET name=$2, description=$3 WHERE uuid=$1", array($mapUUID, $name, $description));
    }

    function changeGroupOfMap($mapUUID, $groupUUID)
    {
        return pg_query_params($this->db_connection, "UPDATE {$this->schema}.map SET groupUUID=$2 WHERE uuid=$1", array($mapUUID, $groupUUID));
    }

    function addUserToGroup($groupUUID, $userUUID)
    {
        return pg_query_params($this->db_connection, "INSERT INTO {$this->schema}.rel_user_group (group_uuid, user_ad_id) VALUES ($1, $2)", array($groupUUID, $userUUID));
    }

    function removeUserFromGroup($groupUUID, $userUUID)
    {
        return pg_query_params($this->db_connection, "DELETE FROM {$this->schema}.rel_user_group WHERE group_uuid=$1 and user_ad_id=$2", array($groupUUID, $userUUID));
    }
}