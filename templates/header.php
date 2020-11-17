
<?php
$name = "user";
// session_start();
// if(isset($_SESSION["name"])){
//     $name = $_SESSION["name"];
// }
    require_once "../../database_connection.php";
    function callback($buffer){
        global $PAGE_TITLE, $PAGE_STYLESHEETS, $PAGE_SCRIPTS;
        $buffer = preg_replace('/(<\s?title\s?>)(.*)(<\s?\/title\s?>)/i', '$1' . $PAGE_TITLE . '$3', $buffer); 
        $buffer = preg_replace('/%STYLE_SHEETS%/i',$PAGE_STYLESHEETS , $buffer);
        $buffer = preg_replace('/%PAGE_SCRIPTS%/i',$PAGE_SCRIPTS , $buffer); 
        return $buffer;
    }
    ob_start("callback");
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/cafeteria/css/bootstrap.css">
    <link rel="stylesheet" href="/cafeteria/css/fontaswesme-all.min.css">
    %STYLE_SHEETS% 
</head>
<body>
    <nav class="navbar navbar-dark bg-dark navbar-expand-sm">
        <a class="navbar-brand font-weight-bolder" href="#">Cafeteria</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-list-4" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-list-4">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a></li>
                <?php if ($name == "Admin"): ?>
                <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                <li class="nav-item"><a class="nav-link " href="views/admin/user/all_users.php">Users</a></li>
                <li class="nav-item"><a class="nav-link" href="#" >Manual Order</a></li>
                <li class="nav-item"><a class="nav-link" href="#" >Checks</a></li>
                <?php endif?>
                <?php if ($name != "Admin"): ?>             
                <li class="nav-item"><a class="nav-link" href="/cafeteria/views/user/user-orders.php" >My Orders</a></li>
                <?php endif?>
                </ul>
            <?php
                if($name != "Admin" && isset($_SESSION["email"])){
                    $sql = "SELECT avatar FROM User WHERE email= :email";
                    $statement = $conn->prepare($sql);
                    $statement->bindValue(":email", $_SESSION["email"] , PDO::PARAM_STR);
                    $statement->execute();
                    $row = $statement->fetch(PDO::FETCH_ASSOC);
                }
            ?>    
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="/cafeteria/images/avatars/<?php echo (isset($row['avatar']) ? $row['avatar'] : 'avatar2.png'); ?>" width="40" height="40" class="rounded-circle">
                   </a>
                </li>
                <li class="nav-item mt-2 nav-link"><?php echo $name;  ?></li>
                <li class="nav-item mt-2 nav-link"><a class="nav-link mt-n2" href="../../logout.php">Log Out<a></li>

            </ul>
        </div>
    </nav>
    
