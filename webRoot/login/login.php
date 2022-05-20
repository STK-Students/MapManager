<?php
require("../ldap/ldap_commands.php");
$ldap_connection = require ("../ldap/ldap_connection.php");

$commands = new LDAP_Commands();
if(isset($_POST['submit-login-form'])){
    echo "hello";
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result_login = $commands->login($ldap_connection, "dc=webdevlocal,dc=com", $username, $password);
    if($result_login){
        header('Location: /home/home.php');
    } else {
        echo "false";
    }
}
?>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="login_style.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Login</title>
</head>
<body>
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
