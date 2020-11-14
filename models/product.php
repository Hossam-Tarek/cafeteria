<?php
    class Product{

        // Database variables
        private $conn;
        private $table = 'Product';

        // Product variables
        private $product_id;
        private $category_id;
        private $name;
        private $price;
        private $image;
        private $available;

        // Constructor intialize Database connection variable
        public function __construct($DB_conn)
        {
            $this -> conn = $DB_conn;
        }

        // Read all
        public function readAll(){
            // Create Query
            $sqlQuery = "SELECT `product_id` AS `id`, `name`, `price`, `image`, `available`, `category_id` FROM " . $this -> table;
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
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
            $sqlQuery = "SELECT `product_id` AS `id`, `name`, `price`, `image`, `available`, `category_id` FROM " . $this -> table . " WHERE `product_id` = ?";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Bind value
            $stmt -> bindParam(1,$this -> product_id);
            // Execute Query
            try {
                $stmt -> execute();
            } catch (Exception $e) {
                return false;
            }
            $post_data = $stmt -> fetch(PDO::FETCH_ASSOC);
            return $post_data;
        }

        // Create product
        public function create(&$errorMessage){
            $errorMessage = '';
            // Create Query
            $sqlQuery = "INSERT INTO ". $this -> table . " (name, price, image, category_id) VALUES (:name , :price, :image, :category_id)";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Bind values
            $stmt -> bindParam(':name',$this -> name);
            $stmt -> bindParam(':price',$this -> price);
            $stmt -> bindParam(':image',$this -> image);
            $stmt -> bindParam(':category_id',$this -> category_id);
            // Execute Query
            try {
                if (!$stmt -> execute()) {
                    $errorMessage = "There is error in product data";
                    throw new Exception("There is error in product data");
                }
            } catch (Exception $e) {
                return false;
            }
            return true;
        }

        // Update product
        public function update(&$errorMessage){
            $errorMessage = '';
            // Update Query
            $sqlQuery = "UPDATE ". $this -> table . " SET name = :name, price = :price, image = :image, category_id = :category_id WHERE product_id = :product_id";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Bind values
            $stmt -> bindParam(':name',$this -> name);
            $stmt -> bindParam(':price',$this -> price);
            $stmt -> bindParam(':image',$this -> image);
            $stmt -> bindParam(':category_id',$this -> category_id);
            $stmt -> bindParam(':product_id',$this -> product_id);
            // Execute Query
            try {
                if (!$stmt -> execute() || $stmt -> rowCount() != 1) {
                    $errorMessage = "There is error in product data";
                    throw new Exception("There is error in category data");
                }
            } catch (Exception $e) {
                return false;
            }
            return true;    
        }

        // Delete product
        public function delete(){
            if(!$this -> readOne()){
                echo json_encode(array("message" => "This product not founded."));
                return 0; // 0 if the product doesn't exist
            } else {
                // Delete Query
                $sqlQuery = "DELETE FROM " . $this -> table . " WHERE product_id = :Pid";
                // Prepare Query
                $stmt = $this -> conn -> prepare($sqlQuery);
                // Bind values
                $stmt -> bindParam(':Pid', $this -> product_id);
                // Execute Query
                try{
                    $stmt -> execute();
                    return 1; // 1 if product deleted
                } catch(PDOException $e){
                    return 2; // 2 if service unavailable
                }
            } 
        }


        // Product Vaibales Getters
        function get_product_id(){
            return $this -> product_id;
        }
        function get_category_id(){
            return $this -> category_id;
        }
        function get_name(){
            return $this -> name;
        }
        function get_price(){
            return $this -> price;
        }
        function get_image(){
            return $this -> image;
        }
        function get_available(){
            return $this -> available;
        }

        // Product Variables Setters
        function set_product_id($product_id){
            if(is_numeric($product_id))
                $this -> product_id = htmlspecialchars(strip_tags($product_id));
        }
        function set_category_id($category_id){
            if(is_numeric($category_id))
                $this -> category_id = htmlspecialchars(strip_tags($category_id));
        }
        function set_name($name){
            $this -> name = htmlspecialchars(strip_tags($name));
        }
        function set_price($price){
            if(is_numeric($price))
                $this -> price = htmlspecialchars(strip_tags($price));
        }
        function set_image($image){
            $this -> image = htmlspecialchars(strip_tags($image));
        }
        function set_available($available){
            $this -> available = htmlspecialchars(strip_tags($available));
        }
    }
