<?php
// get config
$json_config = file_get_contents("ldap_config.json");
$config = json_decode($json_config);

// host information
$hostname = $config->host->hostname;
$port = $config->host->port;

$ldap = ldap_connect($hostname, $port) or die("[Error]: Failed to connect to OpenLDAP");
ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
return $ldap;
?>
