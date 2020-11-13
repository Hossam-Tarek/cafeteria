<?php
     $PAGE_TITLE="All Products";
     $PAGE_STYLESHEETS = "";
     $PAGE_SCRIPTS = "";
     require_once  "../../../templates/header.php"; 
     require_once "../../../database_connection.php";
?>
<div class='container'>
<div class='row'>
<div class='col-sm-12'>
<?php
      try{
      echo "<br>";
      echo "<h1 class=text-center>All Products</h1>";
      echo "<br>";
        $stmt=$conn->prepare("SELECT * FROM `product`");
        $stmt->execute();
        $result=$stmt->fetchAll();       
         echo 
      "<table class='table  table-hover'>
         <thead>
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
     "<td>"."<img src=".'../../../images/products/'.$resul['image']." width='150' height='100' style='border-radius:50%;'>"."</td>".
     "<td> ".$resul['available']. "</td>".
     "<td>" ."<button class='btn btn-danger'>Delete</button>
     <button class='btn btn-primary'>Edit</button>" ."</td>".
     "</tr> </tbody>";
     }
     echo "</table>";
             
     }catch(PDOExeption $e){
         echo "Faild To show All products data".$e->getMessage();
     }
     
?>
</div>
</div>
</div>
<?php
      require_once  "../../../templates/footer.php";
?>
