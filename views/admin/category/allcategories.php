<?php
    session_start();
    require_once "../../../database_connection.php";
    
    if (!isset($_SESSION["account-type"]) || $_SESSION["account-type"] !== "admin") {
        header("Location: /cafeteria/index.php");
        return;
    }
     $PAGE_TITLE="All Categories";
     $PAGE_STYLESHEETS = "";
     $PAGE_SCRIPTS = "<script src='/cafeteria/js/admin/main.js'></script>";
     require_once  "../../../templates/header.php"; 
?>
<div class="container">
    <h1 class="text-center my-3">All Categories</h1>

    <a href="addcategory.php" class="btn btn-success mb-4 ">Add New Category</a>

    <?php
        function ValidateData($data){
            $data=htmlentities($data);
            $data=htmlspecialchars($data);
            $data=trim($data);
            $data=stripslashes($data);
            return $data;
        }
            try {
                $stm=$conn->prepare("SELECT * FROM Category");
                $stm->execute();
                $categories=$stm->fetchAll(); 
              if(count($categories)>0){
                echo "<div class='table-responsive-sm'>";
                    echo "<table class='table'>
                    <tr class='bg-dark text-light'>
                        <th scope='col'>Category Name</th>
                        <th scope='col'>Action</th>
                    </tr>";
            foreach($categories as $category){
                echo "<tr id='".$category['category_id']."'>".              
                "<td> ".ValidateData($category['name'])."</td>".
                "<td>
                <a data-category=".$category['category_id']." class='deletecategory btn btn-danger mt-4'>Delete</a>
                    <a href='editcategory.php?id=".$category['category_id']."' class='btn btn-primary mt-4'>Edit</a></td></tr>";      
                }
                echo "</table>";
                echo "</div>";
              }
                
            } catch (\Throwable $th) {
                throw $th;
        } 
    ?>
</div>
<?php  require_once  "../../../templates/footer.php"; ?>
