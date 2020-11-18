<?php

session_start();

if (!isset($_SESSION["account-type"]) || $_SESSION["account-type"] !== "admin") {
    header("Location: ../../index.php");
    return;
}

$PAGE_TITLE = "Cafeteria";
$PAGE_STYLESHEETS = '<link rel="stylesheet" href="/cafeteria/css/admin/main.css">';
$PAGE_SCRIPTS = '<script src="/cafeteria/js/admin/main.js"></script>';
?>

<?php require_once "../../templates/header.php" ?>

<?php
if (isset($_SESSION["success"])) {
    echo "<p class='success'>". $_SESSION["success"] ."</p>";
    unset($_SESSION["success"]);
}
?>

<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Dashboard</h3>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="#user-menu" data-toggle="collapse" aria-expanded="false"
                   class="dropdown-toggle">User</a>

                <ul class="collapse list-unstyled" id="user-menu">
                    <li><a href="user/all_users.php">All users</a></li>
                    <li><a href="user/add-user.php">Add user</a></li>
                </ul>
            </li>

            <li>
                <a href="#product-menu" data-toggle="collapse" aria-expanded="false"
                   class="dropdown-toggle">Product</a>

                <ul class="collapse list-unstyled" id="product-menu">
                    <li><a href="product/all_products.php">All products</a></li>
                    <li><a href="product/addproduct.php">Add product</a></li>
                </ul>
            </li>

            <li>
                <a href="#category-menu" data-toggle="collapse" aria-expanded="false"
                   class="dropdown-toggle">Category</a>

                <ul class="collapse list-unstyled" id="category-menu">
                    <li><a href="category/allcategories.php">All categories</a></li>
                    <li><a href="category/addcategory.php">Add category</a></li>
                </ul>
            </li>

            <li>
                <a href="#order-menu" data-toggle="collapse" aria-expanded="false"
                   class="dropdown-toggle">Order</a>

                <ul class="collapse list-unstyled" id="order-menu">
                    <li><a href="#">All orders</a></li>
                    <li><a href="#">new order</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="content" class="container-fluid">
        <button type="button" id="sidebarCollapse" class="btn btn-info">
            <i class="fas fa-align-left"></i>
        </button>
    </div>
</div>

<?php require_once "../../templates/footer.php" ?>
