<?php
     $PAGE_TITLE="Update Product";
     $PAGE_STYLESHEETS = "<link rel='stylesheet' href='/cafeteria/css/admin/main.css'>";
     $PAGE_SCRIPTS = "<script src='/cafeteria/js/admin/main.js'></script>";
     require_once  "../../../templates/header.php"; 
     require_once "../../../database_connection.php";
?>
<?php 
    $updated_image="";
    $nameErr=$name="";
    $priceErr=$price="";
    $imgErr=$image="";
    $categoryErr=$category="";

    // Getting Data For Update
    $id=isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']):0;
    $stm=$conn->prepare("SELECT * FROM Product WHERE `product_id`=?");
    $stm->execute([$id]);
    $result=$stm->fetch();

    // Show Category Of Product   
    $stm2=$conn->prepare("SELECT * FROM Category");
    $stm2->execute();
    while($row=$stm2->fetch(PDO::FETCH_ASSOC)){
        $categories[]=$row;
     }

    // Cleaning of Input Values   
    function Clean($data){
        $data=htmlentities($data);
        $data=htmlspecialchars($data);
        $data=trim($data);
        $data=stripslashes($data);
        return $data;
    }

    // Check Inputs Validation    
    if(isset($_POST["id"]) && !empty($_POST["id"])){  
        $id=Clean($_POST['id']);
        $name=Clean($_POST['name']);
        $price=Clean($_POST['price']);
        $category_id=Clean($_POST['category_id']);
        $image=$_FILES['image']['name'];

        // Check Name 
        if (empty($_POST["name"])) {
            $nameErr='Product is Required *';
        }else{
            $stmt2=$conn->prepare("SELECT * FROM Product WHERE name=?");
            $stmt2->execute([$_POST['name']]);
            $return=$stmt2->fetch();
            $count=$stmt2->rowCount();

            // Checking For Name Existence
            if($count > 0 && $_POST['id'] != $return['product_id']){
                $nameErr='Product Already Existed';
            }
        }

        // Price 
        if (empty($_POST["price"])) {
            $priceErr = "price is required *";
        }else{    
            if ($_POST["price"] <= 0) {
                $priceErr = "Invalid Price , Select a Positive Value";
            }
        }

        // Category 
        if (empty($_POST["category_id"])) {
            $categoryErr = "Category is required *";
        }

        // Image 
        if(empty($_FILES['image']['name'])){
                $updated_image=$result['image'];
        }else{
            $validExten=['png','jpg','jpeg'];
            $updated_image=$image;
            $exte=explode('.',$updated_image);
            if(!in_array($exte[count($exte)-1],$validExten)){              
            $imgErr="Please Chose An Image Not File"; 
            }
        }
        
        // Validation Success Then Start Update 
        if(empty($nameErr) && empty($priceErr) && empty($imgErr) && empty($categoryErr)){
                move_uploaded_file($_FILES['image']['tmp_name'],"../../../images/products/".$updated_image);
                $stm3=$conn->prepare("UPDATE Product set name=?,price=?,category_id=?,image=? WHERE product_id=?");
                $stm3->execute([$name,$price,$category_id,$updated_image,$id]);
                $result=$stm3->fetch();
                header('Location:all_products.php');            
        }
    }
?>
<div class="container">
    <h2 class="text-center my-3 text-secondary">Edit product <?php echo " : ". $result['name'] ; ?> </h2>
    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="form-group" enctype="multipart/form-data">
        <input type="hidden" name="id"  value="<?php echo $id ?>">
        
        <label for="product Name"> Name</label>
        <input type="text"  name="name" class="form-control" value="<?php echo $result['name']; ?>">
            <?php if (strlen($nameErr)>0){ ?> 
                    <span class="error text-danger"><?php echo $nameErr; ?></span>
            <?php } ?>

        <label for="Price"> Price</label>
        <input type="text"  name="price" class="form-control" value="<?php echo $result['price']; ?>">
            <?php if (strlen($priceErr)>0){ ?> 
                <span class="error text-danger"><?php echo $priceErr; ?></span>
            <?php } ?>

        <label for="Category id"> Category Name</label>
        <select name='category_id' class="form-control">
                <option value=""></option>
                <?php
                    foreach($categories as $category){ ?>
                    <option value="<?php echo $category['category_id'];?>"
                        <?php if($category['category_id'] === $result['category_id']){echo "selected" ;} ?>
                    > <?php echo $category['name']; ?></option>";
                  <?php }  
                ?>
        </select>
        <?php if (strlen($categoryErr)>0){ ?> 
            <span class="error text-danger"><?php echo $categoryErr; ?></span>
        <?php } ?>

        <label for="Image"> Image</label>
        <input type="file"  name="image" class="form-control">
        <?php if (strlen($imgErr)>0){ ?> 
            <span class="error text-danger"><?php echo $imgErr; ?></span>
        <?php } ?>
       
        <input type="submit" value="Update product" class="btn btn-group btn-success my-4">
    </form>
</div>
<?php  require_once  "../../../templates/footer.php"; ?>
