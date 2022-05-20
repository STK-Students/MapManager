<?php

class LDAPUtils
{
    private $config;
    private $connection;

    function __construct()
    {
        $this->config = json_decode(file_get_contents("../config.json"));
        $this->connect();
    }

    public function connect()
    {
        $uri = $this->config->uri;

        $ldap = ldap_connect($uri) or die("[Error]: Failed to connect to OpenLDAP");
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        $this->connection = $ldap;
    }

    /**
     * Connects to an LDAP instance.
     * @param $connection
     * @param $baseDN
     * @param $username
     * @param $password
     * @return bool if the login was successfully
     */
    public function login($baseDN, $username, $password): bool
    {
        try {
            return ldap_bind($this->connection, "cn=$username,$baseDN", $password);
        } catch (Exception $exception) {
            echo "Message: " . $exception->getMessage();
            return false;
        }
    }

    /**
     * @return mixed this application's ldap config
     */
    public function getConfig()
    {
        return clone $this->config;
    }

    public function get_user($searchDN, $username)
    {
        $res_id = ldap_search($this->connection, $searchDN, "cn=$username");
        $user_entries = ldap_get_entries($this->connection, $res_id);
        @ldap_close($this->connection);
        return $user_entries;
    }

    public function get_group($searchDN, $group)
    {

    }
}

