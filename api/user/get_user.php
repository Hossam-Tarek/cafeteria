<?php
header('Content-Type: application/json');

session_start();
$userData = [];
if (isset($_SESSION["account-type"]) && $_SESSION["account-type"] === "user" && isset($_SESSION['id']) ) {
    $userData['id'] = $_SESSION["id"];
    $userData['type'] = 1; // 1 for user
}
else if (isset($_SESSION["account-type"]) && $_SESSION["account-type"] === "admin") {
    $userData['id'] = -1;
    $userData['type'] = 0; // 0 for admin
}
echo json_encode($userData);

    // Use this Api as --> http://localhost/cafeteria/api/user/get_user.php
