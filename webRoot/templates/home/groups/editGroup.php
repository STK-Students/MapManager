<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
$db = Database::getInstance();
$mode = $_GET["mode"];
if($mode == "create") {
    if(isset($_POST['submit_group_form'])){
        $name = $_POST['input-name'];
        try{
            $userUUID = $_SESSION['authenticatedUser'];
            $groupUUID = $db->addGroup($name);
            $result = $db->addUserToGroup($groupUUID, $userUUID);
            header('Location: /templates/home/home.php');
        } catch(Exception $e){
            print($e->getMessage());
        }
    }
}  else if($mode == "edit"){
    $group = $db->getGroup($_GET["uuid"]);
    if(isset($_POST['submit_group_form'])){
        $db->editGroup($group->getUUID(), $_POST["input-name"]);
        header('Location: /templates/home/home.php');
    }
}

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

        <?php if($mode == "create") echo '<h3>Gruppe erstellen</h3>'; ?>
        <?php if($mode == "edit") echo '<h3>Gruppe bearbeiten</h3>'; ?>
        <form name="create_group_form" method="post">
            <div class="form-group">
                <?php if($mode == "create") echo '<input type="text" class="form-control" name="input-name" id="inputGroupName" placeholder="Name eingeben">'; ?>
                <?php if($mode == "edit") echo '<input type="text" class="form-control" value="'. $group->getName() .'" name="input-name" id="inputGroupName" placeholder="Name eingeben">'; ?>
            </div>

            <?php if($mode == "create") echo '<button type="submit" name="submit_group_form" class="btn btn-danger">Erstellen</button>'; ?>
            <?php if($mode == "edit") echo '<button type="submit" name="submit_group_form" class="btn btn-danger">Ändern</button>'; ?>
        </form>
    </div>
</body>
</html>
