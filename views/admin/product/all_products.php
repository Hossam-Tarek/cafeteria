<?php
     $PAGE_TITLE="All Products";
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
      echo "<h1 class=text-center>All Products</h1>";
      echo "<br>";
        $stmt=$conn->prepare("SELECT * FROM `product`");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     
        $stmt->execute();
     
        $result=$stmt->fetchAll();
            
         echo "<table class=table>
         <thead class=thead-dark>
           <tr>
             <th scope=col>Product_ID</th>
             <th scope=col>Category_ID</th>
             <th scope=col>Name</th>
             <th scope=col>Price</th>
             <th scope=col>Product_Image</th>
             <th scope=col>Product_Avilability</th>
             <th scope=col>Actions</th>

       
           </tr>
         </thead>";
     foreach($result as $resul){
     echo "<tbody> <tr> <td> ".$resul['product_id']. "</td>".
      "<td> ".$resul['category_id']. "</td>".
      "<td> ".$resul['name']. "</td>".
      "<td> ".$resul['price']. "</td>".
     "<td>"."<img src=".$resul['image']." width=150 height=100>"."</td>".
     "<td> ".$resul['available']. "</td>".
     "<td>" ."<button class='btn btn-danger'>Delete</button>
     <button class='btn btn-success'>Edit</button>" ."</td>".


     
     "</tr> </tbody>";
     }
     echo "</table>";
             
     }catch(PDOExeption $e)
     {
         echo "Faild To show All products data".$e->getMessage();
     }
     
     
?>

<?php
        include  '../../../templates/header.php'; 
  
?>