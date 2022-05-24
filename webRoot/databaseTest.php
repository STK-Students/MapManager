<?php
require ("database.php");
$db = new Database("Postgres", "webDevDB", "postgres", "postgres");
$res = $db->addGroup("Vertriebsgruppe");
var_dump($res);
?>
<html>
<head><title>Db-Test</title></head>
<body>

</body>
</html>
