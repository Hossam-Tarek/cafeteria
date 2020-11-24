<?php 
    session_start();
    require_once "../../../database_connection.php";

    if (!isset($_SESSION["account-type"]) || $_SESSION["account-type"] !== "admin") {
        header("Location: /cafeteria/index.php");
        return;
    }
    $id=isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):0;
    $stm=$conn->prepare("DELETE FROM User WHERE user_id=?");
    $stm->execute([$id]);
    