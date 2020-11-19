<?php
session_start();
require_once "../../../database_connection.php";

if (!isset($_SESSION["account-type"]) || $_SESSION["account-type"] !== "admin") {
    header("Location: /cafeteria/index.php");
    return;
}
 $PAGE_TITLE="Checks Orders";
 $PAGE_STYLESHEETS = "";
 $PAGE_SCRIPTS = "<script src='/cafeteria/js/admin/main.js'></script>";
 require_once  "../../../templates/header.php"; 
?>
<?php 
  $conn=new PDO($servername,$username,$password);
  $stmt=$conn->prepare("
                      select u.name ,u.user_id from `User` u ,`order_product` op,`product` p,`order` o ,`room` r
                      where o.user_id=u.user_id
                      and op.order_id=o.order_id
                      and p.product_id=op.product_id
                      and r.room_id=u.room_id
                      group by u.name;
                     ");
  $stmt->execute();
  $users=$stmt->fetchAll();
?>
<br>
<h1 class=text-center>Checks</h1>

<div class="container mt-5">
  <div class="row col-sm-12 d-flex justify-content-center">
   <div class="form-group">
  
    <input class="form-control" class="date" type="date"  id="from_date">
</div>
<div class="form-group ml-3 ">
    <input class="form-control" class="date" type="date"   id="to_date">
</div>
</div>
<div class="form-group col-6 offset-3">
    <select  class="form-control" onchange="Display(this.value)" name="hadray" id="select_user">
    <option value="0" ></option>
      <?php foreach($users as $user){?>
        <option value="<?php echo $user['user_id']; ?>"><?php echo $user['name']?></option>
      <?php }?>
    <option value="0" >All Users</option>
    </select>
  </div>


<table class="table table-bordered mt-5 table-hover ">
  <thead>
  <tr>
      <th scope="col">Name</th>
      <th scope="col" class="offset-3" >Total Amount</th>
    </tr>
  </thead>
  <tbody id="orders_details">
    
   </tbody>
  </table>


<table class="table table-bordered table-hover" id="details">
  <thead>
  <tr>
      <th scope="col">date</th>
      <th scope="col " class="offset-3">Total Amount</th>
    </tr>
  </thead>
  <tbody id="order_details">
 
  
  </tbody>
</table>

</div>

<div class="container col-sm-12 " id="orders">
<div class="row  d-flex justify-content-center " >

    <div class="card col-sm-3 mr-2" style="width: 18rem;" id="orderdetails">
      
      </div>
    </div>

</div>
</div>
</div>
<?php
      require_once  "../../../templates/footer.php";
?>





