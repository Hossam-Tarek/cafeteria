<?php
   $PAGE_TITLE = "All Orders";
   $PAGE_STYLESHEETS = "";
   $PAGE_SCRIPTS = "";
   require_once "../../../templates/header.php";   
   require_once "../../../templates/database_connection.php";        
?>

<?php
  try{ 
      echo "<br>";
      echo "<h1 class=text-center>All Orders</h1>";
      echo "<br>";

        $stmt=$conn->prepare("SELECT * FROM `order`");
       $stmt->execute();
        $result=$stmt->fetchAll();
        
         echo 
    "<table class='table  table-bordered table-hover'>
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
            <td> ".$resul['order_id']. "</td>".
            "<td> ".$resul['user_id']. "</td>".
            "<td> ".$resul['room_id']. "</td>".
            "<td> ".$resul['date']. "</td>".
            "<td> ".$resul['status']. "</td>".
            "<td> ".$resul['comment']. "</td>".
            "<td>" ."<button class='btn btn-danger'>Delete</button>
            <button class='btn btn-warning'>Edit</button>" ."</td>".
     "</tr> 
</tbody>";
     }
     echo "</table>";
             
     }catch(PDOExeption $e){
         echo "Faild To show All orders data".$e->getMessage();
     }    
?>

<?php
     
     require_once  "../../../templates/footer.php"; 
?>
