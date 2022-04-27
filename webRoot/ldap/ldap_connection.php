<?php
// get config
$commands = new LDAP_Commands();
$config = $commands->get_ldap_config();

// host information
$hostname = $config->host->hostname;
$port = $config->host->port;

$ldap = ldap_connect($hostname, $port) or die("[Error]: Failed to connect to OpenLDAP");
ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
return $ldap;
?>
