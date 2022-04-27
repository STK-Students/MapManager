<?php
require("ldap_commands.php");
$ldap_connection = require("ldap_connection.php");

$commands = new LDAP_Commands();
$config = $commands->get_ldap_config();

// Mock Login
$login_result = $commands->login($ldap_connection, $config->domain->baseDN, $config->adminUser->username, $config->adminUser->password);
var_dump($login_result);
?>
