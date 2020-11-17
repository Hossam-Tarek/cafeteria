<?php
    require_once "../../database_connection.php";
    session_start();
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
    }
    class UserOrder
    {
        public $query;
        public $stmt;
        public $numrows;
        public $row;
        //fetch all orders for user
        public function getOrderData()
        {
            global $conn , $id;
            $dateFrom = str_replace('/', '-', $_GET['dateFrom']); //datepicker in the format of date in sql stmt
            $dateTo = str_replace('/', '-', $_GET['dateTo']);

            //get price of each order
            $ordePriceQuery = "SELECT SUM(price*quantity) as price
                               FROM `Product` INNER JOIN `Order_Product` 
                               ON Order_Product.product_id=Product.product_id 
                               INNER JOIN `Order` ON Order_Product.order_id=`Order`.order_id 
                               AND( date(`Order`.`date`) BETWEEN '" . $dateFrom . "' AND  '" . $dateTo . "')
                               AND `Order`.`user_id`= $id GROUP BY Order_Product.order_id ORDER BY `Order`.`date` ASC";

            $stmt2 = ($conn)->prepare($ordePriceQuery);
            $stmt2->execute();
            $rowsno = $stmt2->rowCount();
            $row2 = ($stmt2)->fetchAll(PDO::FETCH_ASSOC);
            $orderPrices =[];

            foreach (($row2) as $record => $column){
                $orderPrices[]=($column["price"]);  //save data in array
            }
            $i=0;   //for loop in orderPrices

            //fetch all orders between 2 dates
            $this->query = "SELECT * FROM `Order` WHERE user_id = $id
                            AND date(date) BETWEEN  '" . $dateFrom . "' AND  '" . $dateTo . "'
                            ORDER BY `date` ASC;"; 
            $this->stmt = ($conn)->prepare($this->query);
            ($this->stmt)->execute();
            $this->numrows = ($this->stmt)->rowCount();

            if (($this->numrows) > 0) {
                $this->row = ($this->stmt)->fetchAll(PDO::FETCH_ASSOC);
                foreach (($this->row) as $record => $column):
                    $status;
                    if ($column['status'] == 0) {$status = "processing";} 
                    elseif ($column['status'] == 1) {$status = "done";} 
                    elseif ($column['status'] == 2) {$status = "out of delievry";}
            $id = ($column['order_id']);
?>

                    <tr role='button' id="<?php echo "id".$id; ?>"  onclick="getProducts(<?php echo $id; ?>)">  <!-- click  on order do function with order id as parameter -->
                        <td> <?php echo date('Y-m-d', strtotime($column['date'])); ?></td>
                        <td><?php echo ($status); ?></td>
                        <td><?php echo ($orderPrices[0+$i]." LE"); ?></td>
                        <td><?php if ($column['status'] == 0): ?>
                            <a class="btn btn-danger" onclick="deleteOrder(<?php echo $id; ?>)">cancel</a>
                <?php endif;?>
                        </td>
                    </tr>

                <?php
                    $i++;
                    endforeach;
                }
        }
    // get products related to order id
        public function getProducts()
        {
            global $conn , $id;
            $this->query = "SELECT image , name , quantity , price FROM Order_Product,Product 
                            WHERE Product.product_id=Order_Product.product_id 
                            AND Order_Product.order_id=" . $_GET["id"] . "";   //id from get method
            $this->stmt = $conn->prepare($this->query);
            ($this->stmt)->execute();
            $this->numrows = ($this->stmt)->rowCount();
            if (($this->numrows) > 0) {
                $this->row = ($this->stmt)->fetchAll(PDO::FETCH_ASSOC);?>
                <?php
                    foreach (($this->row) as $record => $column):
                ?>

                    <div class="d-inline-block product" >
                        <img class="product-image d-block rounded ml-3" src="../../images/products/<?php echo ($column["image"]); ?>"/>
                        <span class="d-block"><?php echo ($column["name"]); ?></span>
                        <span class="d-block"><?php echo ($column["quantity"]); ?></span>
                        <span class=""><?php echo $column["price"]*$column["quantity"] ?> Le</span>
                    </div>

                <?php
                    endforeach; 
            }
        }

        //delete order
        public function deleteOrder()
        {
            global $conn;
            $this->query = "delete from `Order` WHERE
                            order_id=" . $_GET["deleteorder"] . ""; //id from get method
            $this->stmt = $conn->prepare($this->query);
            ($this->stmt)->execute();
        }

    }
    $obj = new UserOrder();
    $obj->getOrderData();
    if (isset($_GET["id"])) {
        $obj->getProducts();
    }
    if (isset($_GET["deleteorder"])) {
        $obj->deleteOrder();
    }
?>


