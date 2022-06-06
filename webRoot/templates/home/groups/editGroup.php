<?php
require("../../../database.php");
$db = new Database("Postgres", "webDevDB", "postgres", "postgres");
$mode = $_GET["mode"];
if($mode == "create") {
    if(isset($_POST['submit_group_form'])){
        $name = $_POST['input-name'];
        try{
            $result = $db->addGroup($name);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"
            integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT"
            crossorigin="anonymous"></script>
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
        <h3>Gruppe erstellen</h3>
        <form name="create_group_form" method="post">
            <div class="form-group">
                <?php if($mode == "create") echo '<input type="text" class="form-control" name="input-name" id="inputGroupName" placeholder="Name eingeben">'; ?>
                <?php if($mode == "edit") echo '<input type="text" class="form-control" value="'. $group->getName() .'" name="input-name" id="inputGroupName" placeholder="Name eingeben">'; ?>
            </div>
            <button type="submit" name="submit_group_form" class="btn btn-danger">Submit</button>
        </form>
    </div>
</body>
</html>
