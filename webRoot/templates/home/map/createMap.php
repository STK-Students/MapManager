<?php
require $_SERVER['DOCUMENT_ROOT'] . "api/database.php";

$db =  Database::getInstance();;
$group = $_GET['uuid'];

if(isset($_POST['submit_group_form'])){
    $name = $_POST['input-name'];
    $description = $_POST['input-description'];
    $creationDate = date('Y-m-d');
    try {
        $db->addMap($name, $description, $creationDate, $group);
        header('Location: /templates/edit/edit.php');
    } catch(Exception $e){
        print($e->getMessage());
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
            <input type="text" class="form-control" name="input-name" id="inputGroupName" placeholder="Name eingeben">
            <input type="text" class="form-control" name="input-description" id="inputGroupDescription" placeholder="Beschreibung eingeben">
        </div>
        <button type="submit" name="submit_group_form" class="btn btn-danger">Submit</button>
    </form>
</div>
</body>
</html>

