<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
$db = Database::getInstance();
$group_uuid = $_GET["uuid"];

?>
    <html>
    <head>
        <title>Gruppe erstellen</title>
        <title>Home</title>
        <link rel="stylesheet" href="../../../dependencies/Bootstrap/css/bootstrap.min.css">
        <script src="../../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../../../dependencies/jQuery/jQuery.js"></script>
        <style>
            body{
                background-color: #DC3545;
            }
            .main{
                position: relative;
                background-color: white;
                width: 500px;
                height: 250px;
                margin: 250px auto 0px auto;
                border-radius: 4px;
                text-align: center;
            }
            .main h3{
                position: relative;
                top: 10px;
                text-align: center;
            }
            .form-group{
                width: 80%;
                margin: 30px auto 0px auto;
            }
            .main button{
                margin-top: 40px;
            }
        </style>
    </head>
    <body>
    <div class="main">
        <h3>Mitarbeiter anzeigen</h3>
        <form name="create_group_form" method="post">
            <div class="form-group">
                <table>
                    <tr>
                        <th>Vorname</th>
                        <th>Nachname</th>
                        <th>Benutzername</th>
                        <th>Löschen</th>
                    </tr>
                    <?php
                    $users = $db->getUsersFromGroup($group_uuid);
                    for($i = 0; $i < count($users); $i++){
                        $item = (object)$users[$i];
                        echo "<tr>";
                        echo "<td>" . $item->getFirstname() . "</td>";
                        echo "<td>" . $item->getLastname() . "</td>";
                        echo "<tr>" . $item->getUsername() . "</tr>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <button type="submit" name="submit_group_form" class="btn btn-danger">Erstellen</button>
        </form>
    </div>
    </body>
    </html>
<?php
