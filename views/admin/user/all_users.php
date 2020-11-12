
<?php
 $PAGE_TITLE="All Users";
 include  '../templates/header.php'; 
   
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
      echo "<h1 class=text-center>All Users</h1>";
      echo "<br>";
  
      
        $stmt=$conn->prepare("SELECT * FROM `user`");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     
        $stmt->execute();
     
        $result=$stmt->fetchAll();
            
         echo "<table class=table>
         <thead class=thead-dark>
           <tr>
             <th scope=col>ID</th>
             <th scope=col>Room_id</th>
             <th scope=col>Name</th>
             <th scope=col>Email</th>
             <th scope=col>Password</th>
             <th scope=col>Exetra_Info</th>
             <th scope=col>Avatar</th>
             <th scope=col>Actions</th>

       
           </tr>
         </thead>";
     foreach($result as $resul){
     echo "<tbody> <tr> <td> ".$resul['user_id']. "</td>".
      "<td> ".$resul['room_id']. "</td>".
      "<td> ".$resul['name']. "</td>".
      "<td> ".$resul['email']. "</td>".
      "<td> ".$resul['password']. "</td>".
      "<td> ".$resul['extra_info']. "</td>".
     "<td>"."<img src=".$resul['avatar']." width=150 height=100>"."</td>".
     "<td>" ."<button class='btn btn-danger'>Delete</button>
     <button class='btn btn-success'>Edit</button>" ."</td>".
     
     "</tr> </tbody>";
     }
     echo "</table>";
             
  
     }catch(PDOExeption $e)
     {
         echo "Faild To show All users data".$e->getMessage();
     }
     
     
?>

<?php
   include  '../templates/footer.php'; 
  
?>