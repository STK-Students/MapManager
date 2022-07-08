<?php
?>
    <!doctype html>
    <html lang="de">
    <head>
        <meta charset="UTF-8"> <!--Ermöglicht einfache Eingabe von Sonderzeichen-->
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!--Bootstrap responsive design meta tag-->
        <link rel="stylesheet" href="../../dependencies/Bootstrap/css/bootstrap.min.css">
        <script src="../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../../dependencies/Bootstrap/js/formValidator.js" defer></script>
        <link rel="stylesheet" href="auth_style.css">
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
        <h1>Registrierung</h1>
        <form name="register_form" id="auth_form" method="post">
            <div class="row row-cols-1">
                <div class="form-floating gx-1">
                    <input type="text" id="firstname" name="firstname" class="form-control"
                           placeholder="Vorname">
                    <label for="firstname">Vorname</label>
                </div>
                <div class="form-floating gx-1">
                    <input type="text" id="lastname" name="lastname" class="form-control"
                           placeholder="Nachname">
                    <label for="lastname">Nachname</label>
                </div>
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
                <div class="form-floating gx-1 gy-3">
                    <input type="password" id="passwordRepeat" name="passwordRepeat" class="form-control"
                           placeholder="Passwort wiederholen">
                    <label for="passwordRepeat">Passwort wiederholen</label>
                </div>
                <br>
            </div>
            <div class="row">
                <input type="submit" name="submit-register-form" class="btn btn-danger gy-4 col-6 ms-md-auto" value="Registrieren">
                <p class="col-3 col-auto"></p>
            </div>
        </form>
    </div>

    </body>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

$db = Database::getInstance();

if (isset($_POST['submit-register-form'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($password == $_POST['passwordRepeat']) {
        echo "Test";
        if($db->registerUser($firstname, $lastname, $username, $password)){
            $_SESSION['authenticated'] = true;
            header('Location: /templates/home/home.php');
        }else {
            echo "Ungültige Anmeldedaten";
        }
    } else {
        echo "Passwörter unterscheiden sich";
    }
}
?>
    </html><?php
