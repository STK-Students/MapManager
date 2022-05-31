<html lang="de">

<?php
$ldap = require("../ldap/ldapUtils.php");

if(isset($_POST['submit-login-form'])){
    echo "hello";
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result_login = $ldap->login("dc=webdevlocal,dc=com", $username, $password);
    if($result_login){
        header('Location: /home/home.php');
    } else {
        echo "false";
    }
}
?>
<html>
<head>
    <meta charset="UTF-8"> <!--Ermöglicht einfache Eingabe von Sonderzeichen-->

    <title>MapManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="login/login_style.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            text-align: center;
        }

        form {
            margin: 0 auto;
            width: 500px;
        }
    </style>
</head>
<body>
<img src="../.media/Stadt_Koeln_Logo.jpg" alt="Logo der Stadt Köln">

<div class="wrapper">
    <div id="form_content">
        <h1>Login</h1>
        <form name="login_form" id="login_form" method="post">
            <input type="text" id="username" name="username" class="login_form_text form-control" placeholder="Benutzername">
            <input type="password" id="password" name="password" class="login_form_text form-control" placeholder="Passwort">
            <br>
            <input type="submit" name="submit-login-form" class="btn btn-danger" value="Anmelden">
        </form>
    </div>
</div>
</body>
</html>