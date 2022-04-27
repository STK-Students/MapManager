<?php
require("ldap_commands.php");
$ldap_connection = require("ldap_connection.php");

$commands = new LDAP_Commands();
$config = $commands->get_ldap_config();

// Mock Login
$login_result = $commands->login($ldap_connection, $config->domain->baseDN, $config->adminUser->username, $config->adminUser->password);
if($login_result){
    $user = $commands->get_user($ldap_connection, $config->domain->baseDN, "Max Mustermann");
    var_dump($user);
}
?>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Test</title>
</head>
<body>
<button type="button" class="btn btn-primary" >Test</button>
</body>
</html>
