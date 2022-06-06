<?php
session_start();
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8"> <!--Ermöglicht einfache Eingabe von Sonderzeichen-->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!--Bootstrap responsive design meta tag-->
    <link rel="stylesheet" href="../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/Bootstrap/js/formValidator.js" defer></script>
    <link rel="stylesheet" href="login_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <title>MapManager</title>
</head>
<body>
<img src="../../.media/Stadt_Koeln_Logo.jpg" class="center" alt="Logo der Stadt Köln">


<div id="form_content" class="container-sm wrapper">
    <h1>Login</h1>
    <form name="login_form" id="login_form" method="post">
        <div class="row row-cols-1">
        <div class="form-floating gx-1">
            <input type="text" id="username" name="username" class="form-control"
                   placeholder="Benutzername">
            <label for="username">Benutzername</label>
        </div>
        <div class="form-floating gx-1 gy-3">
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Passwort">
            <label for="password">Passwort</label>
        </div>
        <br>
    </div>
        <div class="row">
            <input type="submit" name="submit-login-form" class="btn btn-danger gy-4 col-6 ms-md-auto" value="Anmelden">
            <p class="col-3 col-auto"></p>
        </div>
    </form>
</div>

</body>
<?php
require("../ldap/ldapUtils.php");

$ldap = new LDAPUtils();

if (isset($_POST['submit-login-form'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($ldap->login("dc=webdevlocal,dc=com", $username, $password)) {
        $_SESSION['authenticated'] = true;
        header('Location: /templates/home/home.php');
    } else {
        echo "Ungültige Anmeldedaten";
    }
}
?>
</html>