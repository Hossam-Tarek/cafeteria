<?php
session_start();
require_once "../../../database_connection.php";

if (!isset($_SESSION["account-type"]) || $_SESSION["account-type"] !== "admin") {
    header("Location: /cafeteria/index.php");
    return;
}

$PAGE_TITLE="All Users";
 $PAGE_STYLESHEETS = "";
 $PAGE_SCRIPTS = "<script src='/cafeteria/js/admin/main.js'></script>";
 require_once  "../../../templates/header.php"; 
 require_once "../../../database_connection.php";  
?>
<div class='container'>
<div class="row ">
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
      echo "<h1 class=text-center>All Users</h1>";
      echo "<br>";    
        $stmt=$conn->prepare("SELECT * FROM `User`");
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
          echo "<tr id='".$user['user_id']."'><th scope='row'>".ValidateData($user['user_id'])."</th>".              
          "<td> ".ValidateData($user['name'])."</td>".
          "<td> ".ValidateData($user['room_id'])."</td>".
          "<td> ".ValidateData($user['email'])."</td>".
          "<td> ".ValidateData($user['extra_info'])."</td>".
          "<td> "."<img width=100 height=100 style='border-radius:50%' src=".'../../../images/avatars/'.ValidateData($user['avatar']).">"."</td>".
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
