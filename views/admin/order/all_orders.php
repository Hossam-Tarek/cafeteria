<?php
   $PAGE_TITLE = "All Orders";
   $PAGE_STYLESHEETS = "<link rel='stylesheet' href='../../../css/admin/admin.css'>";
   $PAGE_SCRIPTS = "<script src='/cafeteria/js/admin/main.js'></script>";
   require_once "../../../templates/header.php";   
   require_once "../../../database_connection.php";        
?>
<div class='container' id="container">
<div class='row'>
<div class='col-sm-12'>
<?php
    function ValidateData($data){
      $data=htmlentities($data);
      $data=htmlspecialchars($data);
      $data=trim($data);
      $data=stripslashes($data);
      return $data;
    }
  try{ 
      echo "<br>";
      echo "<h1 class=text-center>All Orders</h1>";
      echo "<br>";
        $stmt=$conn->prepare("SELECT * FROM `Order`");
       $stmt->execute();
        $result=$stmt->fetchAll();
         echo  
    "<table class='table  table-hover '>
        <thead >
           <tr>
             <th scope=col>Order_ID</th>
             <th scope=col>User_ID</th>
             <th scope=col>Room_ID</th>
             <th scope=col>Date</th>
             <th scope=col>Status</th>
             <th scope=col>Comment</th>
             <th scope=col>Actions</th>   
           </tr>
        </thead>";
     foreach($result as $resul){
     echo 
 "<tbody>
        <tr> 
            <td> ".ValidateData($resul['order_id']). "</td>".
            "<td> ".ValidateData($resul['user_id']). "</td>".
            "<td> ".ValidateData($resul['room_id']). "</td>".
            "<td> ".ValidateData($resul['date']). "</td>".
            "<td> ".ValidateData($resul['status']). "</td>".
            "<td> ".ValidateData($resul['comment']). "</td>".
            "<td>" ."<button class='btn btn-danger'>Delete</button>
            <button class='btn btn-primary'>Edit</button>" ."</td>".
     "</tr> 
</tbody>";
     }
     echo "</table>";
             
     }catch(PDOExeption $e){
         echo "Faild To show All orders data".$e->getMessage();
     }    
?>
</div>
</div>
</div>
<?php
     
     require_once  "../../../templates/footer.php"; 
?>
