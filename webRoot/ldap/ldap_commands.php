<?php
class LDAP_Commands {
    public function get_ldap_config() {
        $json_config = file_get_contents("../ldap_config.json", true);
        return json_decode($json_config);
    }

    public function login($connection, $baseDN, $username, $password){
        try{
            $login = ldap_bind($connection, "cn=$username,$baseDN", $password);
            return $login;
        }catch (Exception $exception){
            echo "Message: " . $exception->getMessage();
            return false;
        }
    }
    public function get_user($connection, $searchDN, $username) {
        $res_id = ldap_search($connection, $searchDN, "cn=$username");
        $user_entries = ldap_get_entries($connection, $res_id);
        @ldap_close($connection);
        return $user_entries;
    }
    public function get_group($connection, $searchDN, $group) {

    }
}
?>