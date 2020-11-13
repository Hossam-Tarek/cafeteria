<?php
     $PAGE_TITLE="All Categories";
     $PAGE_STYLESHEETS = "";
     $PAGE_SCRIPTS = "";
     require_once  "../../../templates/header.php"; 
     require_once "../../../database_connection.php";
?>
<div class="container">
    <h1 class="text-center my-3">All Categories</h1>
    <a href="#" class="btn btn-success mb-4 offset-2">Add New Category</a>
    <?php
            try {
                $stm=$conn->prepare("SELECT * FROM category");
                $stm->execute();
                $categories=$stm->fetchAll(); 
              if(count($categories)>0){
                echo "<div class='row'><div class='col-md-8 offset-2'>";
                    echo "<table class='table'>
                    <tr class='bg-dark text-light'>
                        <th scope='col'>ID</th>
                        <th scope='col'>Category Name</th>
                        <th scope='col'>Action</th>
                    </tr>";
            foreach($categories as $category){
                echo "<tr id='".$category['category_id']."'><th scope='row'>".$category['category_id']."</th>".              
                "<td> ".$category['name']."</td>".
                "<td>
                <a data-category=".$category['category_id']." class='deletecategory btn btn-danger mt-4'>Delete</a>
                    <a href='editcategory.php?id=".$category['category_id']."' class='btn btn-primary mt-4'>Update</a>                    </td></tr>";      
                }
                echo "</table>";
                echo "</div></div >";
              }
                
            } catch (\Throwable $th) {
                throw $th;
        } 
    ?>
</div>
<?php  require_once  "../../../templates/footer.php"; ?>
