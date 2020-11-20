<?php
  $from= $_GET['from'];
  $to= $_GET['to'];
  $stmt=$conn->prepare("SELECT u.name ,u.user_id from `User` u ,`Order_product` op,`Product` p,`Order` o ,`Room` r
                          where o.user_id=u.user_id
                          and op.order_id=o.order_id
                          and p.product_id=op.product_id
                          and r.room_id=u.room_id
                          and date >=? And date <= ?
                          group by u.name
                       ");

  $stmt->execute([$from,$to]);
  $res=$stmt->fetchAll(); 
  foreach($res as $r){ ?>
      <option value="<?php echo $r['user_id'] ?>"><?php echo $r['name']; ?></option>
  <?php }?>
  <option value="0" >All Users</option>

