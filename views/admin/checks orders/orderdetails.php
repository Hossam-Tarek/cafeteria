<?php require_once "../../../database_connection.php";?>
<?php
$id=$_GET['id'];

$stmt=$conn->prepare("SELECT o.order_id, date, sum(price*quantity) as amount  
                        from `User`,`Order` o,Product,Order_Product
                        where o.order_id=Order_Product.order_id
                        and Product.product_id=Order_Product.product_id
                        and `User`.`user_id`=o.`user_id`
                        and o.`user_id`= ?
                        GROUP BY o.order_id;
                    ");
$stmt->execute([$id]);
$resulte=$stmt->fetchAll();
foreach($resulte as $order){ ?>
    <tr data-target="<?php echo $id; ?>" onclick="Displaydetails(<?php echo $order['order_id']?>)">
        <td><?php echo $order['date']; ?></td>
        <td><?php echo $order['amount']; ?></td>
    </tr>
<?php } ?> 
