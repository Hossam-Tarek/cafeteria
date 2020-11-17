<?php
     $PAGE_TITLE="Add Category";
     $PAGE_STYLESHEETS = "";
     $PAGE_SCRIPTS = "<script src='/cafeteria/js/admin/main.js'></script>";
     require_once  "../../../templates/header.php"; 
     require_once "../../../database_connection.php";
?>
<?php 
    $nameErr="";
    // Cleaning of Input    
    function Clean($data){
        $data=htmlentities($data);
        $data=htmlspecialchars($data);
        $data=trim($data);
        $data=stripslashes($data);
        return $data;
    }
  if($_SERVER['REQUEST_METHOD']=='POST'){
    $name=Clean($_POST['name']);

    // Check For Validation 
    if (empty($_POST["name"])) {
        $nameErr='Category is Required *';
    }else{
        if(preg_match("/^([a-zA-Z' ]+)$/",$name)){
            $stmt2=$conn->prepare("SELECT * FROM Category WHERE name=?");
            $stmt2->execute([$_POST['name']]);
            $return=$stmt2->fetch();
            $count=$stmt2->rowCount();
            if($count > 0){
                $nameErr='Category Already Existed';
            }
        }else{
            $nameErr='Invalid name given.';
        }
    }
   
    // Validation Success Then Start Inserting 
    if(empty($nameErr)){
            $stm3=$conn->prepare("INSERT INTO Category (name) VALUES (?)");
            $stm3->execute([$name]);
            $result=$stm3->fetch();
            header('Location:allcategories.php');   
    }
  }
    
?>
<div class="container">
    <h1 class="text-center text-secondary my-3">Add New Category </h1>
    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="form-group" id='form1'>
        <label for="Category Name">Category Name</label>
        <input type="text" name="name" class="form-control">
            <?php if (strlen($nameErr)>0){ ?> 
                <span class="error text-danger"
                         
                ><?php echo $nameErr; ?></span>
            <?php } ?>
        <input type="submit" name="submit" value="Add Category" class="btn btn-group btn-success my-4">
    </form>
</div>
<?php  require_once  "../../../templates/footer.php"; ?>
