<?php require_once('../../../database_connection.php'); ?>
<?php
    $id=isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):0;
    $stm=$conn->prepare("DELETE FROM Product WHERE product_id=?");
    $stm->execute([$id]);  
    