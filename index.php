<?php

session_start();

$PAGE_TITLE = "Cafeteria";
$PAGE_STYLESHEETS = '<link rel="stylesheet" href="/cafeteria/css/main.css">';
$PAGE_SCRIPTS = "";
?>

<?php require_once "templates/header.php" ?>

<?php
if (isset($_SESSION["success"])) {
    echo "<p class='success'>". $_SESSION["success"] ."</p>";
    unset($_SESSION["success"]);
}
?>

<?php require_once "templates/footer.php" ?>
