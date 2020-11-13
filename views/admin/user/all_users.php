<?php
 $PAGE_TITLE="All Users";
 $PAGE_STYLESHEETS = "";
 $PAGE_SCRIPTS = "";
 require_once  "../../../templates/header.php"; 
 require_once "../../../database_connection.php";  
?>
<div class='container'>
<div class="row ">
<div class='col-sm-12'>
<?php  
  try{
      echo "<br>";
      echo "<h1 class=text-center>All Users</h1>";
      echo "<br>";    
        $stmt=$conn->prepare("SELECT * FROM `user`");
        $stmt->execute();
        $users=$stmt->fetchAll();
       if(count($users)>0){     
         echo "<table class='table  table-hover'>
         <thead  >
           <tr class='text-light bg-dark'>
             <th scope=col>ID</th>
             <th scope=col>Name</th>
             <th scope=col>Room_id</th>
             <th scope=col>Email</th>
             <th scope=col>Exetra_Info</th>
             <th scope=col>Avatar</th>
             <th scope=col>Actions</th>
           </tr>
         </thead>";
         foreach($users as $user){
          echo "<tr id='".$user['user_id']."'><th scope='row'>".$user['user_id']."</th>".              
          "<td> ".$user['name']."</td>".
          "<td> ".$user['room_id']."</td>".
          "<td> ".$user['email']."</td>".
          "<td> ".$user['extra_info']."</td>".
          "<td> "."<img width=100 height=100 style='border-radius:50%' src=".'../../../images/avatars/'.$user['avatar'].">"."</td>".
          "<td>
              <a data-user=".$user['user_id']." class='deleteuser btn btn-danger mt-4'>Delete</a>
              <a href='edituser.php?id=".$user['user_id']."' class='btn btn-primary mt-4'>Edit</a></td></tr>";      
          }
     echo "</table>";
      }    
     }catch(PDOExeption $e){
         echo "Faild To show All users data".$e->getMessage();
     }
?>
</div>
</div>
</div>
<?php require_once "../../../templates/footer.php";?>

