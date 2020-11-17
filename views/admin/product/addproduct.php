<?php
     $PAGE_TITLE="Add Product";
     $PAGE_STYLESHEETS = "<link rel='stylesheet' href='/cafeteria/css/admin/main.css'>";
     $PAGE_SCRIPTS = "<script src='/cafeteria/js/admin/main.js'></script>";
     require_once  "../../../templates/header.php"; 
     require_once "../../../database_connection.php";
?>
<?php 
    
    $nameErr=$name="";
    $priceErr=$price="";
    $imgErr=$image="";
    $categoryErr=$category="";
    // Show Category Of Product 
    $stm2=$conn->prepare("SELECT * FROM Category");
    $stm2->execute();
    while($row=$stm2->fetch(PDO::FETCH_ASSOC)){
        $categories[]=$row;
     }
    // Cleaning of Input   
    function Clean($data){
        $data=htmlentities($data);
        $data=htmlspecialchars($data);
        $data=trim($data);
        $data=stripslashes($data);
        return $data;
    }

    // Check Validation    
    if($_SERVER['REQUEST_METHOD']=='POST'){  
        $name=Clean($_POST['name']);
        $price=Clean($_POST['price']);
        $category_id=Clean($_POST['category_id']);
        $image=$_FILES['image']['name'];
        // Check Name 
        if (empty($_POST["name"])) {
            $nameErr='Product is Required *';
        }else{
            if(preg_match("/^([a-zA-Z' ]+)$/",$name)){
                $stmt2=$conn->prepare("SELECT * FROM Product WHERE name=?");
                $stmt2->execute([$_POST['name']]);
                $return=$stmt2->fetch();
                $count=$stmt2->rowCount();
                // Check For Existence
                if($count > 0 ){
                    $nameErr='Product Already Existed';
                }
            }else{
                $nameErr='Invalid name given.';
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
                $imgErr="Image is required *";
        }else{
            $validExten=['png','jpg','jpeg'];
            $exte=explode('.',$image);
            if(!in_array($exte[count($exte)-1],$validExten)){              
            $imgErr="Please Chose An Image Not File"; 
            }
        }
        // Validation Success Then Start Update 
        if(empty($nameErr) && empty($priceErr) && empty($imgErr) && empty($categoryErr)){
                move_uploaded_file($_FILES['image']['tmp_name'],"productimage/".$image);
                $stm3=$conn->prepare("INSERT INTO Product(name,price,category_id,image) 
                VALUES(:name,:price,:category_id,:image)");
                $stm3->bindParam(":name",$name);
                $stm3->bindParam(":price",$price);
                $stm3->bindParam(":category_id",$category_id);
                $stm3->bindParam(":image",$image);
                $stm3->execute();
                header('Location:all_products.php');            
        }
    }
?>
<div class="container">
    <h2 class="text-center my-3 text-secondary">Add product </h2>
    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="form-group" enctype="multipart/form-data">
        
        <label for="product Name"> Name</label>
        <input type="text"  name="name" class="form-control" >
            <?php if (strlen($nameErr)>0){ ?> 
                    <span class="error text-danger"
                          style="display:block;float:right;position:relative;
                          top: -33px;right: 24px;"
                     ><?php echo $nameErr; ?></span>
            <?php } ?>

        <label for="Price"> Price</label>
        <input type="text"  name="price" class="form-control">
            <?php if (strlen($priceErr)>0){ ?> 
                <span class="error text-danger"
                          style="display:block;float:right;position:relative;
                          top: -33px;right: 24px;"
                ><?php echo $priceErr; ?></span>
            <?php } ?>

        <label for="Category id"> Category Name</label>
        <select name='category_id' class="form-control">
                <option value=""></option>
                <?php
                    foreach($categories as $category){ ?>
                    <option value="<?php echo $category['category_id'];?>">
                        <?php echo $category['name']; ?>
                    </option>";
                  <?php }  
                ?>
        </select>
        <?php if (strlen($categoryErr)>0){ ?> 
            <span class="error text-danger" 
                          style="display:block;float:right;position:relative;
                          top: -33px;right: 24px;"
            ><?php echo $categoryErr; ?></span>

        <?php } ?>

        <label for="Image"> Image</label>
        <input type="file"  name="image" class="form-control">
        <?php if (strlen($imgErr)>0){ ?> 
            <span class="error text-danger"
                          style="display:block;float:right;position:relative;
                          top: -33px;right: 24px;"
            ><?php echo $imgErr; ?></span>
        <?php } ?>
       
        <input type="submit" value="Add product" class="btn btn-group btn-success my-4">
    </form>
</div>
<?php  require_once  "../../../templates/footer.php"; ?>
 
