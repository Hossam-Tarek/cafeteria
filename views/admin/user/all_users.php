<?php
 $PAGE_TITLE="All Users";
 $PAGE_STYLESHEETS = "";
 $PAGE_SCRIPTS = "";
 require_once  "../../../templates/header.php"; 
 require_once "../../../templates/database_connection.php";  
?>
<?php  
  try{
      echo "<br>";
      echo "<h1 class=text-center>All Users</h1>";
      echo "<br>";    
        $stmt=$conn->prepare("SELECT * FROM `user`");
        $stmt->execute();
        $result=$stmt->fetchAll();
            
         echo "<table class='table table-bordered table-hover'>
         <thead  >
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
     <button class='btn btn-warning'>Edit</button>" ."</td>".
     
     "</tr> </tbody>";
     }
     echo "</table>";
            
     }catch(PDOExeption $e){
         echo "Faild To show All users data".$e->getMessage();
     }
       
?>

<?php
   require_once "../../../templates/footer.php"; 
?>
