<?php
require("ldapUtils.php");

$ldap = new LDAPUtils();
$config = $ldap->getConfig();

// Mock Login
$login_result = $ldap->login($config->domain->baseDN, $config->adminUser->username, $config->adminUser->password);
if ($login_result) {
    $user = $ldap->get_user($config->domain->baseDN, "Max Mustermann");
    var_dump($user);
    echo "<br>Value: " . array_pop($user);

}
?>
<html lang="de">
<head>
    <title>Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <button type="button" class="btn btn-primary">Test</button>
</body>
</html>
