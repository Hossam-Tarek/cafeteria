<?php require_once "../../../database_connection.php";?>
<?php
    $id=$_GET['id'];
    $orders="";
    if($id==0){
            $stmt=$con->prepare("SELECT u.name,u.user_id ,sum(price*quantity) as totalprice
                                from `User` u ,`order_product` op,`product` p,`order` o
                                where o.user_id=u.user_id
                                and op.order_id=o.order_id
                                and p.product_id=op.product_id
                                group by u.name
                              ");
            $stmt->execute();
            $orders=$stmt->fetchAll();    
            foreach($orders as $order){ ?>
                <tr  data-target="<?php echo $order['user_id']; ?>"  onclick="DisplayOrderDetails(<?php echo $order['user_id'] ?>)">
                    <td><?php echo $order['name']; ?></td>
                    <td><?php echo $order['totalprice'] ?></td>
                </tr> 
            <?php } 
    }
    else{ 
        $stmt=$con->prepare("  select u.name ,sum(price*quantity) as totalprice
        from `User` u ,`Order_product` op,`Product` p,`Order` o
        where o.user_id=u.user_id
        and op.order_id=o.order_id
        and p.product_id=op.product_id
        and u.user_id=?
        group by u.name
        ");
        $stmt->execute([$id]);
        $orders=$stmt->fetchAll();
        foreach($orders as $order){ ?>
            <tr  data-target="<?php echo $id; ?>"  onclick="DisplayOrderDetails(<?php echo $id ?>)">
                 <td><?php echo $order['name'] ?></td>
                <td><?php echo $order['totalprice'] ?></td>
            </tr> 
        <?php }
   } 
?>

