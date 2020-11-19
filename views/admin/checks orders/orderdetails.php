<?php require_once "../../../database_connection.php";?>
<?php
$id=$_GET['id'];

$stmt=$con->prepare("SELECT `Order`.order_id,`Order`.`user_id` ,date,price*quantity as amount  
                     from `User` ,`Order`,Product,Order_product,`Room`
                        where `Order`.order_id=order_product.order_id
                        and Product.product_id=order_product.product_id
                        and `User`.`user_id`=`order`.`user_id`
                        and `Order`.`user_id`=?
                        GROUP by `Order`.order_id
                    ");
$stmt->execute([$id]);
$resulte=$stmt->fetchAll();
foreach($resulte as $order){ ?>
    <tr data-target="<?php echo $id; ?>" onclick="Displaydetails(<?php echo $order['order_id']?>)">
        <td><?php echo $order['date']; ?></td>
        <td><?php echo $order['amount']; ?></td>
    </tr>
<?php } ?> 
