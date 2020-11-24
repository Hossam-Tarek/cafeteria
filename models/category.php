<?php
    class Category{

        // Database variables
        private $conn;
        private $table = 'Category';

        // Category variables
        private $category_id;
        private $name;

        // Constructor intialize Database connection variable
        public function __construct($DB_conn)
        {
            $this -> conn = $DB_conn;
        }

        // Read all
        public function readAll(){
            // Create Query
            $sqlQuery = "SELECT `category_id` AS `id`, `name` FROM " . $this -> table . " ORDER BY `category_id`";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Execute Query
            try {
                $stmt -> execute();
            } catch (Exception $e) {
                return false;
            }
            return $stmt;
        }

        // Read one
        public function readOne(){
            // Create Query
            $sqlQuery = "SELECT `category_id` AS `id`, `name` FROM " . $this -> table . " WHERE `category_id` = ?";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Bind value
            $stmt -> bindParam(1,$this -> category_id);
            // Execute Query
            try {
                $stmt -> execute();
            } catch (Exception $e) {
                return false;
            }
            $category_data = $stmt -> fetch(PDO::FETCH_ASSOC);
            return $category_data;
        }

        // Create Category
        public function create(&$errorMessage){
            $errorMessage = '';
            // Create Query
            $sqlQuery = "INSERT INTO ". $this -> table . " (name) VALUES (:name)";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Bind values
            $stmt -> bindParam(':name',$this -> name);
            // Execute Query
            try {
                if (!$stmt -> execute()) {
                    $errorMessage = "There is error in category data";
                    throw new Exception("There is error in category data");
                }
            } catch (Exception $e) {
                return false;
            }
            return true;
        }

        // Update Category
        public function update(&$errorMessage){
            $errorMessage = '';
            // Update Query
            $sqlQuery = "UPDATE ". $this -> table . " SET name = :name WHERE category_id = :category_id";
            // Prepare Query
            $stmt = $this -> conn -> prepare($sqlQuery);
            // Bind values
            $stmt -> bindParam(':category_id',$this -> category_id);
            $stmt -> bindParam(':name',$this -> name);
            // Execute Query
            try {
                if (!$stmt -> execute() || $stmt -> rowCount() != 1) {
                    $errorMessage = "There is error in category data";
                    throw new Exception("There is error in category data");
                }
                // echo  ; die();
            } catch (Exception $e) {
                return false;
            }
            return true;    
        }

        // Delete Category
        public function delete(){
            if(!$this -> readOne()){
                echo json_encode(array("message" => "This category not founded."));
                return 0; // 0 if the Category doesn't exist
            } else {
                // Delete Query
                $sqlQuery = "DELETE FROM " . $this -> table . " WHERE category_id = :category_id";
                // Prepare Query
                $stmt = $this -> conn -> prepare($sqlQuery);
                // Bind values
                $stmt -> bindParam(':category_id', $this -> category_id);
                // Execute Query
                try{
                    $stmt -> execute();
                    return 1; // 1 if Category deleted
                } catch(Exception $e){
                    return 2; // 2 if service unavailable
                }
            } 
        }


        // Category Vaibales Getters
        function get_category_id(){
            return $this -> category_id;
        }
        function get_name(){
            return $this -> name;
        }

        // Category Variables Setters
        function set_category_id($category_id){
            if(is_numeric($category_id))
                $this -> category_id = htmlspecialchars(strip_tags($category_id));
        }
        function set_name($name){
            $this -> name = htmlspecialchars(strip_tags($name));
        }
    }
