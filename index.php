<?php

session_start();
$PAGE_TITLE = "Cafeteria";
$PAGE_STYLESHEETS = '<link rel="stylesheet" href="/cafeteria/css/main.css">';
$PAGE_SCRIPTS = "";
require_once "templates/header.php";
?>
<div class="img-container">
    <img src="img/coffee-time.jpg" id="img-background" alt="coffee time">
</div>

<?php
require_once "templates/footer.php" 
?>