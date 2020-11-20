<!-- Display Details for every order-->
<?php require_once "../../../database_connection.php";?>
<?php 
    $id=$_GET['id'];
    $stmt=$conn->prepare("SELECT image , name ,price, quantity FROM Order_Product,Product 
                            WHERE Product.product_id=Order_Product.product_id
                            and Order_Product.order_id=?
                        ");
    $stmt->execute([$id]);
    $orders_details=$stmt->fetchAll();
   
    foreach($orders_details as $order){?>
    
    <img  class="card-img-top pt-3" width="150px" height="200px" src="<?php echo "../../../images/products/".$order['image']?>" >
   <div class="card-body">
    <h5 class="card-title"><?php echo "quantity: ".$order['quantity']?></h5>
    <p class="card-text"><?php echo "price: ".$order['price']?></p>
 
 <?php }?>
   