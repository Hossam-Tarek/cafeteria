<?php
     $PAGE_TITLE="All Products";
     $PAGE_STYLESHEETS = "";
     $PAGE_SCRIPTS = "<script src='/cafeteria/js/admin/main.js'></script>";
     require_once  "../../../templates/header.php"; 
     require_once "../../../database_connection.php";
?>
<div class='container'>
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
      echo "<h1 class=text-center>All Products</h1>";
      echo "<a href='addproduct.php' class='btn btn-success my-3'>Add New Product</a>";
      echo "<br>";
        $stmt=$conn->prepare("SELECT * FROM `Product`");
        $stmt->execute();
        $products=$stmt->fetchAll(); 
        if(count($products)>0){     
         echo "<table class='table  table-hover'>
         <thead class='text-light bg-dark'>
           <tr>
             <th scope=col>Product_ID</th>
             <th scope=col>Name</th>
             <th scope=col>Price</th>
             <th scope=col>Category_ID</th>
             <th scope=col>Product_Image</th>
             <th scope=col>Actions</th>
           </tr>
         </thead>";
          foreach($products as $product){
            echo "<tr id='".$product['product_id']."'><th scope='row'>".ValidateData($product['product_id'])."</th>".              
            "<td> ".ValidateData($product['name'])."</td>".
            "<td> ".ValidateData($product['price'])."</td>".
            "<td> ".ValidateData($product['category_id'])."</td>".
            "<td> "."<img width=100 height=100 style='border-radius:50%' src=".'../../../images/products/'.ValidateData($product['image']).">"."</td>".
            "<td>
                <a data-product=".$product['product_id']." class='deleteproduct btn btn-danger mt-4'>Delete</a>
                <a href='editproduct.php?id=".$product['product_id']."' class='btn btn-primary mt-4'>Edit</a></td></tr>";      
            }
          echo "</table>";
          }
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
