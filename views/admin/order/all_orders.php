<?php
   $PAGE_TITLE="All Orders";
   include  '../../../templates/header.php'; 
           
?>

<?php
      $servername="mysql:host=localhost;dbname=cafeteria";
      $username="root";
      $password="";
      
      try
      {
          $conn=new PDO($servername,$username,$password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      echo "<br>";
      echo "<h1 class=text-center>All Orders</h1>";
      echo "<br>";

        $stmt=$conn->prepare("SELECT * FROM `order`");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       $stmt->execute();
        $result=$stmt->fetchAll();
        
         echo 
    "<table class='table '>
        <thead class=thead-dark>
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
            <button class='btn btn-success'>Edit</button>" ."</td>".
     "</tr> 
</tbody>";
     }
     echo "</table>";
             
     }catch(PDOExeption $e)
     {
         echo "Faild To show All users data".$e->getMessage();
     }
     
     
?>

<?php
     
     include  '../../../templates/footer.php'; 
   
  
?>