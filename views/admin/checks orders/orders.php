<?php require_once "../../../database_connection.php"; ?>
<?php
$id = $_GET['id'];
$orders = "";
if ($id == 0) {
    $stmt = $conn->prepare("SELECT u.name, u.user_id , sum(op.quantity * p.price) as totalprice
    from `User` u , `Order` o  ,  `Order_Product` op , `Product` p
    where u.user_id = o.user_id
    and o.order_id = op.order_id
    and op.product_id = p.product_id
    GROUP BY u.user_id;
");
    $stmt->execute();
    $orders = $stmt->fetchAll();
    foreach ($orders as $order) { ?>
        <tr data-target="<?php echo $order['user_id']; ?>" onclick="DisplayOrderDetails(<?php echo $order['user_id'] ?>)">
            <td><?php echo $order['name']; ?></td>
            <td><?php echo $order['totalprice'] ?></td>
        </tr>
    <?php }
} else {
    $stmt = $conn->prepare("SELECT u.name, u.user_id , sum(op.quantity * p.price) as totalprice
    from `User` u , `Order` o  ,  `Order_Product` op , `Product` p
    where u.user_id = o.user_id
    and o.order_id = op.order_id
    and op.product_id = p.product_id
    and u.user_id = ?
    GROUP BY u.user_id;
");
    $stmt->execute([$id]);
    $orders = $stmt->fetchAll();
    foreach ($orders as $order) { ?>
        <tr data-target="<?php echo $id; ?>" onclick="DisplayOrderDetails(<?php echo $id ?>)">
            <td><?php echo $order['name'] ?></td>
            <td><?php echo $order['totalprice'] ?></td>
        </tr>
<?php }
}
?>