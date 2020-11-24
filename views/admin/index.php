<?php

require_once "../../database_connection.php";

session_start();

if (!isset($_SESSION["account-type"]) || $_SESSION["account-type"] !== "admin") {
    header("Location: ../../index.php");
    return;
}

$PAGE_TITLE = "Cafeteria";
$PAGE_STYLESHEETS = '<link rel="stylesheet" href="/cafeteria/css/admin/main.css">';
$PAGE_SCRIPTS = '<script src="/cafeteria/js/admin/main.js"></script>';

function getNumber($table)
{
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM " . $table;
    $stm = $conn->prepare($sql);
    $stm->bindValue(":tableName", $table, PDO::PARAM_STR);
    $stm->execute();
    return $stm->fetch()["count"];
}

$categoryNum = getNumber("Category");
$orderNum = getNumber("`Order`");
$productNum = getNumber("Product");
$roomNum = getNumber("Room");
$userNum = getNumber("User");
?>

<?php require_once "../../templates/header.php" ?>

<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Dashboard</h3>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="#user-menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">User</a>

                <ul class="collapse list-unstyled" id="user-menu">
                    <li><a href="user/all_users.php">All users</a></li>
                    <li><a href="user/add-user.php">Add user</a></li>
                </ul>
            </li>

            <li>
                <a href="#product-menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Product</a>

                <ul class="collapse list-unstyled" id="product-menu">
                    <li><a href="product/all_products.php">All products</a></li>
                    <li><a href="product/addproduct.php">Add product</a></li>
                </ul>
            </li>

            <li>
                <a href="#category-menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Category</a>

                <ul class="collapse list-unstyled" id="category-menu">
                    <li><a href="category/allcategories.php">All categories</a></li>
                    <li><a href="category/addcategory.php">Add category</a></li>
                </ul>
            </li>

            <li>
                <a href="#order-menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Order</a>

                <ul class="collapse list-unstyled" id="order-menu">
                    <li><a href="order/all_orders.php">All orders</a></li>
                    <li><a href="../user/order/create_order.php">new order</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="content" class="container-fluid">
        <button type="button" id="sidebarCollapse" class="btn btn-info">
            <i class="fas fa-align-left"></i>
        </button>

        <?php
        if (isset($_SESSION["success"])) {
            echo "<p class='success'>" . $_SESSION["success"] . "</p>";
            unset($_SESSION["success"]);
        }
        ?>

        <div class="container">
            <div class="row justify-content-center">
                <div class="card m-1" style="width: 16.5rem;" data-link="user/all_users.php">
                    <div class="card-body">
                        <i class="fas fa-users card-icon"></i>
                        <h6 class="card-title text-muted">Users</h6>
                        <p class="card-text"><?= htmlentities($userNum) ?></p>
                    </div>
                </div>

                <div class="card m-1" style="width: 16.5rem;" data-link="">
                    <div class="card-body">
                        <i class="fas fa-store-alt card-icon"></i>
                        <h6 class="card-title text-muted">Rooms</h6>
                        <p class="card-text"><?= htmlentities($roomNum) ?></p>
                    </div>
                </div>

                <div class="card m-1" style="width: 16.5rem;" data-link="category/allcategories.php">
                    <div class="card-body">
                        <i class="fas fa-tags card-icon"></i>
                        <h6 class="card-title text-muted">Categories</h6>
                        <p class="card-text"><?= htmlentities($categoryNum) ?></p>
                    </div>
                </div>

                <div class="card m-1" style="width: 16.5rem;" data-link="product/all_products.php">
                    <div class="card-body">
                        <i class="fas fa-coffee card-icon"></i>
                        <h6 class="card-title text-muted">Products</h6>
                        <p class="card-text"><?= htmlentities($productNum) ?></p>
                    </div>
                </div>

                <div class="card m-1" style="width: 16.5rem;" data-link="order/all_orders.php">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart card-icon"></i>
                        <h6 class="card-title text-muted">Orders</h6>
                        <p class="card-text"><?= htmlentities($orderNum) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../templates/footer.php" ?>