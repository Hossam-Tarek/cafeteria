<?php
    require_once 'DB_config_data.php';
    class Database extends DB_config_data{
        private $conn;
        // Database Connect
        public function connect(){
            $this -> conn = null;
            $dsn = Parent::BDMS_NAME . ':host=' . Parent::HOST . ';dnname=' . Parent::BD_NAME;
            try {
                $this -> conn = new PDO('mysql:host=localhost;dbname=cafeteria','root','0000');
            } catch (PDOException $e) {
                echo 'Connection Error: ' . $e -> getMessage();
            }
            return $this -> conn;
        }

        public function close_connection(){
            $this->conn->close();
        }

        function __destruct()
        {
            // $this->conn->close();
        }
    }
