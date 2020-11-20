<?php require_once "../../../database_connection.php"; ?>
<?php
$from = $_GET['from'];
$to = $_GET['to'];
$stmt = $conn->prepare("SELECT u.name ,u.user_id from `User` u ,`Order_Product` op,`Product` p,`Order` o
                          where o.user_id=u.user_id
                          and op.order_id=o.order_id
                          and p.product_id=op.product_id
                          and date between ? AND ?
                          group by u.user_id
                          order by u.name
                       ");
$stmt->bindParam(1,$from);
$stmt->bindParam(2,$to);
$stmt->execute();
// $stmt->execute([$from, $to]);
$res = $stmt->fetchAll();
var_dump($res);
foreach ($res as $r) { ?>
  <option value="<?php echo $r['user_id'] ?>"><?php echo $r['name']; ?></option>
<?php } ?>
<option value="0">All Users</option>