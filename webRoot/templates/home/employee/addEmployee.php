<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
$groupUUID = $_GET['uuid'];
$inviteCode = "http://localhost/templates/home/home.php?inviteCode=" . $groupUUID;

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
        .main button{
            margin-top: 40px;
        }
        input {
            margin-top: 10px;
            width: 200px;
        }
    </style>
</head>
<body>
<div class="main">
    <h3>Mitarbeiter hinzufügen</h3>
    <p>Mit diesem Einladungscode können Sie Mitarbeiter zu Ihrer Gruppe hinzufügen</p>
    <input type="text" id="inviteCode" name="inviteCode" class="form-control"
           placeholder="Einladungscode" value="<?php echo $inviteCode; ?>">
</div>
</body>
</html>
