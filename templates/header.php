<?php 
$user = "admin"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.css">
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
                    <?php if(isset($admin)): ?>
                    <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                    <li class="nav-item"><a class="nav-link " href="#" >Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" >Manual Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" >Checks</a></li>
                    <?php endif ?>
                    <?php if(isset($user)): ?>
                    <li class="nav-item"><a class="nav-link" href="#" >My Orders</a></li>         
                    <?php endif ?>

                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="1.jpg" width="40" height="40" class="rounded-circle">
                        </a>
                    </li>
                    <li class="nav-item mt-2 nav-link"><?php if(isset($user)) echo "Waheed"; else echo "Admin" 
                     ?></li>   
                </ul>
            </div>
        </nav>

