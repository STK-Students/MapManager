<?php
class LDAP_Commands {
    public function get_ldap_config() {
        $json_config = file_get_contents("ldap_config.json");
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
}
?>