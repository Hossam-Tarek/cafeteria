<?php
    class Order{

        // Database variables
        private $conn;
        private $OrderTable = 'Order';
        private $OrderProductTable = 'Order_Product';

        // Category variables
        private $order_id;
        private $user_id;
        private $room_id;
        private $date;
        private $status;
        private $comment;
        private $products_id_quantity;
        // [ {'product_id' => 'product_quantity'},{},...] [ {'5' => '2'},.... ] if get data from request to create order

        // Constructor intialize Database connection variable
        public function __construct($DB_conn)
        {
            $this -> conn = $DB_conn;
        }

        // Read all
        public function readAll(){
            // Create Query
            $sqlQuery = "SELECT `order_id` AS `id` FROM `" . $this -> OrderTable  . "` WHERE `user_id` = ?";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Bind value
            $stmt -> bindParam(1,$this -> user_id);
            // Execute Query
            try {
                $stmt -> execute();
            } catch (Exception $e) {
                // var_dump($stmt -> errorInfo());
                return false;
            }
            return $stmt;
        }

        // Read one
        public function readOne(){
            // Create Query
            $sqlQuery = "SELECT `order_id` AS `id` FROM `" . $this -> OrderTable . "` WHERE `order_id` = ?";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Bind value
            $stmt -> bindParam(1,$this -> order_id);
            // Execute Query
            
            try {
                $stmt -> execute();
            } catch (Exception $e) {
                return false;
            }
            $order_data = $stmt -> fetch(PDO::FETCH_ASSOC);
            return $order_data;
        }

        // Create Category
        public function create(&$errorMessage){
            $errorMessage = '';
            // Create Queries
            $sqlOrderQuery = "INSERT INTO `". $this -> OrderTable . "` (user_id, room_id, comment) VALUES (:user_id , :room_id, :comment)";
            $sqlOrderProductQuery = "INSERT INTO ". $this -> OrderProductTable . " (order_id, product_id, quantity) VALUES ";

            // Prepare Query
            $orderStmt = $this -> conn -> prepare($sqlOrderQuery);
            // Bind values
            $orderStmt -> bindParam(':user_id',$this -> user_id);
            $orderStmt -> bindParam(':room_id',$this -> room_id);
            $orderStmt -> bindParam(':comment',$this -> comment);

            // Execute Queries
            try {
                $this -> conn -> beginTransaction();
                if(!$orderStmt -> execute()){
                    $errorMessage = "There is error in order";
                    throw new Exception("There is error in order");
                }
                $lastOrderId =  $this -> conn -> lastInsertId();

                // Create Query
                foreach ($this -> products_id_quantity as $key => $product) {
                    $product -> id =  htmlspecialchars(strip_tags($product -> id));
                    $product -> quantity =  htmlspecialchars(strip_tags($product -> quantity));
                    if(is_numeric($product -> id) && is_numeric($product -> quantity))
                        $sqlOrderProductQuery .= "( '" . $lastOrderId . "' , " .  $product -> id . ", " . $product -> quantity . "),";
                    else 
                        throw new Exception('Product id & Product quantity must be numeric');
                }

                $sqlOrderProductQuery = substr($sqlOrderProductQuery,0,strlen($sqlOrderProductQuery)-1);
                // Prepare Query
                $orderProductsStmt = $this -> conn -> prepare($sqlOrderProductQuery); 
                // Execute Queries
                if (!$orderProductsStmt -> execute()) {
                    $errorMessage = "There is error in products data";
                    throw new Exception("There is error in products");
                }
                    
                // Commit
                $this -> conn -> commit();
            } catch (Exception $e) {
                if($this -> conn -> inTransaction()) // Rollback if there is error
                    $this -> conn -> rollback();
                return false;
            }
            return true;
        }

        // Update Category
        public function update(&$errorMessage){
            $errorMessage = '';
            // Update Query
            $sqlQuery = "UPDATE `". $this -> OrderTable . "` SET status = :status WHERE order_id = :order_id";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Bind values
            $stmt -> bindParam(':status',$this -> status);
            $stmt -> bindParam(':order_id',$this -> order_id);
            // Execute Query
            try {
                if (($this -> status) < 0 || ($this -> status) > 2 ||
                    !$stmt -> execute() || $stmt -> rowCount() != 1)
                     {
                    $errorMessage = "There is error in order data";
                    throw new Exception("There is error in category data");
                }
            } catch (Exception $e) {
                return false;
            }
            return true;  
        }

        // Delete Category
        public function delete(){
            if(!$this -> readOne()){
                echo json_encode(array("message" => "This order not founded."));
                return 0; // 0 if the Category doesn't exist
            } else {
                // Delete Query
                $sqlQuery = "DELETE FROM `" . $this -> OrderTable . "` WHERE order_id = :order_id";
                // Prepare Query
                $stmt = $this -> conn -> prepare($sqlQuery);
                // Bind values
                $stmt -> bindParam(':order_id', $this -> order_id);
                // Execute Query
                try{
                    $stmt -> execute();
                    return 1; // 1 if Category deleted
                } catch(Exception $e){
                    return 2; // 2 if service unavailable
                }
            } 
        }


        // Order Vaibales Getters
        function get_order_id(){
            return $this -> order_id;
        }

        function get_user_id(){
            return $this -> user_id;
        }

        function get_room_id(){
            return $this -> room_id;
        }

        function get_date(){
            return $this -> date;
        }

        function get_status(){
            return $this -> status;
        }

        function get_comment(){
            return $this -> comment;
        }

        function get_products_id_quantity(){
            return $this -> products_id_quantity;
        }


        // Category Variables Setters

        function set_order_id($order_id){
            if(is_numeric($order_id))
                $this -> order_id = htmlspecialchars(strip_tags($order_id));
        }

        function set_user_id($user_id){
            if(is_numeric($user_id))
                $this -> user_id = htmlspecialchars(strip_tags($user_id));
        }

        function set_room_id($room_id){
            if(is_numeric($room_id))
                $this -> room_id = htmlspecialchars(strip_tags($room_id));
        }

        function set_date($date){
            $this -> date = htmlspecialchars(strip_tags($date));
        }

        function set_status($status){
            if(is_numeric($status))
                $this -> status = htmlspecialchars(strip_tags($status));
        }

        function set_comment($comment){
            $this -> comment = htmlspecialchars(strip_tags($comment));
        }

        function set_products_id_quantity($products_id_quantity){
            // echo 'Set';die();
            if(is_array($products_id_quantity))
                $this -> products_id_quantity  = $products_id_quantity;
        }
    }
