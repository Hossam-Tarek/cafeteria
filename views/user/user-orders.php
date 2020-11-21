<?php
    session_start();
    $PAGE_TITLE = "UserOrder";
    $PAGE_STYLESHEETS = '<link rel="stylesheet" href="../../css/user/main.css">';
    $PAGE_SCRIPTS = '<script src="../../js/user/main.js"></script>';
    require_once "../../templates/header.php";
    require_once "../../database_connection.php";

    $id;
    if (isset($_SESSION["email"]) && isset($_SESSION["account-type"])) {
        if ($_SESSION["account-type"] === "admin") {
            header("Location: /cafeteria/views/admin/index.php");
            return;
        }
        if ($_SESSION["account-type"] === "user") {
            $sql = "SELECT user_id FROM User WHERE email = :email";
            $statement = $conn->prepare($sql);
            $statement->bindValue(":email", $_SESSION["email"], PDO::PARAM_STR);
            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            $id = $row["user_id"];
        }
    }else{
        header("Location: /cafeteria/login.php");
    }
?>

<div class="container" id="user-container">
    <h1 class="mt-3 mb-3">My Orders</h1>
    <form class="row">
        <div class="form-group col-md-3">
            <label for="date-from">Date From</label>
            <input class="form-control" type="date" id="date-from"/>
        </div>
        <div class="form-group col-md-3">
            <label for="date-to">Date to </label>
            <input class="form-control" type="date" id="date-to"/>
        </div>
    </form>

    <div id="error" class=" alert alert-danger"></div>

    <table class="table border" id="submit-date">
        <thead>
            <tr>
                <th scope="col">Order date</th>
                <th scope="col">Status</th>
                <th scope="col">Amount</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody id="getData">

    <?php

        $ordePriceQuery = "
            SELECT SUM(price*quantity) as price
            FROM `Product` INNER JOIN `Order_Product`
            ON Order_Product.product_id=Product.product_id
            INNER JOIN `Order` ON Order_Product.order_id=`Order`.order_id
            AND `Order`.`user_id`=$id GROUP BY Order_Product.order_id 
            ORDER BY `Order`.`date` ASC";

        $stmt2 = $conn->prepare($ordePriceQuery);
        $stmt2->execute();
        $rowsno = $stmt2->rowCount();
        $row2 = ($stmt2)->fetchAll(PDO::FETCH_ASSOC);
        $orderPrices = [];
        foreach (($row2) as $record => $column) {
            $orderPrices[] = $column["price"];           //save data in array
        }
        $i = 0;          
        //select all data                                               
        $stm = $conn->prepare("SELECT * FROM `Order` where user_id=$id ORDER BY `date` ASC");
        $stm->execute();
        $orders = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (count($orders) > 0) {
            foreach ($orders as $record => $column):
                $status;
                if ($column['status'] == 0) {$status = "processing";} 
                elseif ($column['status'] == 1) {$status = "done";}
                elseif ($column['status'] == 2) {$status = "out of delievry";}
                $id = ($column['order_id']);
    ?>

            <tr role='button' class="user-order" data-id="<?php echo $id; ?>">  <!-- click  on order do function with order id as parameter -->
                <td><?php echo date('Y-m-d', strtotime($column['date'])); ?></td>
                <td><?php echo ($status); ?></td>
                <td data-id="<?php echo $id ?>" data-price="<?php echo ($orderPrices[0 + $i]); ?>"><?php echo ($orderPrices[0 + $i]); ?> LE</td>
                <td><?php if ($column['status'] == 0): ?>
                        <a class="btn btn-danger cancel-button" data-id="<?php echo $id; ?>">cancel</a>
                    <?php endif;?>
                </td>
            </tr>

    <?php 
        $i++;
        endforeach;
        }
    ?>

        </tbody>
    </table>

    <div id="products" class="p-3 align-items-center justify-content-center"></div>

    <div class="font-weight-bolder float-right">
         <span >Total:</span>
         <span id="total-sum"><?php echo(array_sum($orderPrices));  ?></span><span> LE</span>
    </div>

</div>

<?php
    require_once "../../templates/footer.php";
?>


