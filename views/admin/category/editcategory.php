<?php
     $PAGE_TITLE="Update Category";
     $PAGE_STYLESHEETS = "";
     $PAGE_SCRIPTS = "<script src='/cafeteria/js/admin/main.js'></script>";
     require_once  "../../../templates/header.php"; 
     require_once "../../../database_connection.php";
?>
<?php 
    $nameErr=$name="";
    
    // Getting Data For Update
    $id=isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']):0;
    $stm=$conn->prepare("SELECT * FROM Category WHERE `category_id`=?");
    $stm->execute([$id]);
    $result=$stm->fetch(); 
  
    // Cleaning of Input    
    function Clean($data){
        $data=htmlentities($data);
        $data=htmlspecialchars($data);
        $data=trim($data);
        $data=stripslashes($data);
        return $data;
    }
    // Check For Validation 

    if(isset($_POST["id"]) && !empty($_POST["id"])){  
        $id=Clean($_POST['id']);
        $name=Clean($_POST['name']);

    // Name 
        if (empty($_POST["name"])) {
            $nameErr='Category is Required *';
        }else{
            $stmt2=$conn->prepare("SELECT * FROM Category WHERE name=?");
            $stmt2->execute([$_POST['name']]);
            $return=$stmt2->fetch();
            $count=$stmt2->rowCount();
            if($count > 0 && $_POST['id'] != $return['category_id']){
                $nameErr='Category Already Existed';
            }
        }

    // Validation Success Then Start Updating 
        if(empty($nameErr)){
                $stm3=$conn->prepare("UPDATE Category set name=? WHERE category_id=?");
                $stm3->execute([$name,$id]);
                $result=$stm3->fetch();
                header('Location:allcategories.php');   
        }
    }
?>
<div class="container">
    <h1 class="text-center text-secondary my-3">Edit Category <?php  echo " : ". $result['name'] ; ?> </h1>
    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="form-group" id='form1'>
        <input type="hidden" name="id"  value="<?php echo $id ?>">
        <label for="Category Name">Category Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $result['name']; ?>">
            <?php if (strlen($nameErr)>0){ ?> 
                <span class="error text-danger"
                          style="display:block;float:right;position:relative;
                          top: -33px;right: 24px;"
                ><?php echo $nameErr; ?></span>
            <?php } ?>
        <input type="submit" name="submit" value="Update Category" class="btn btn-group btn-success my-4">
    </form>
</div>
<?php  require_once  "../../../templates/footer.php"; ?>
