<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

$db = Database::getInstance();;
$groupUUID = $_GET['uuid'];
$group = $db->getGroup($groupUUID);

if (isset($_POST['submit_group_form'])) {
    try {
        $db->removeGroup($group->getUUID());
        echo '<div class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="d-flex">
                <div class="toast-body">
                    Hello, world! This is a toast message.
               </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
            </div>';
        header('Location: /templates/home/home.php');
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
