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

        // public function getSumOrders(){

        // }



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
            $row2 = ($stmt2)->fetchAll(PDO::FETCH_ASSOC);

            $orders =[];   //json object
            $sum;
            //get total sum of orders
            foreach($row2 as $key=>$value){   
                $sum[] = $value["price"];
            }
            $sum = array_sum($sum);
         
            $orders["prices"] = $row2;
            $orders["sum"] = $sum;

            //fetch all orders between 2 dates
            $this->query = "SELECT * FROM `Order` WHERE user_id = $id
                            AND date(date) BETWEEN  '" . $dateFrom . "' AND  '" . $dateTo . "'
                            ORDER BY `date` ASC;"; 
            $this->stmt = ($conn)->prepare($this->query);
            ($this->stmt)->execute();
            $this->numrows = ($this->stmt)->rowCount();

            $this->row = ($this->stmt)->fetchAll(PDO::FETCH_ASSOC);
            $orders["orders"] = $this->row;
            return (json_encode($orders));   //return json object
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
            $this->row = ($this->stmt)->fetchAll(PDO::FETCH_ASSOC);
            return (json_encode($this->row));
        }

        //delete order
        public function deleteOrder()
        {
            global $conn;
            $this->query = "delete from `Order` WHERE
                            order_id=" . $_GET["deleteOrderId"] . ""; //id from get method
            $this->stmt = $conn->prepare($this->query);
            ($this->stmt)->execute();

            $query = "SELECT SUM(price*quantity) as price
                      FROM `Product` INNER JOIN `Order_Product`
                      ON Order_Product.product_id=Product.product_id
                      INNER JOIN `Order`
                      ON Order_Product.order_id=`Order`.order_id 
                      WHERE Order_Product.order_id =".$_GET["deleteOrderId"];
            $statement->$conn->prepare($query);
            $statement->execute();
            $row = $statement->fetchAll(PDO::FETCH_ASSOC);
            return (json_encode($row));
        }
    }
    $obj = new UserOrder();
    
    if (isset($_GET["dateFrom"])){
       echo($obj->getOrderData());  
    }
    if (isset($_GET["id"])) {
        echo($obj->getProducts());
    }
    if (isset($_GET["deleteOrderId"])) {
        echo($obj->deleteOrder());
    }
?>


