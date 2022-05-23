<?php
require ("database.php");
$db = new Database("Postgres", "webDevDB", "postgres", "postgres");
$groups = $db->getGroups();
var_dump($groups);
?>
<html>
<head><title>Db-Test</title></head>
<body>

</body>
</html>
