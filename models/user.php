<?php
class User
{

    // Database variables
    private $conn;
    private $table = 'User';

    // User variables
    private $id;
    private $name;

    // Constructor intialize Database connection variable
    public function __construct($DB_conn)
    {
        $this->conn = $DB_conn;
    }

    // Read all
    public function readAll()
    {
        // Create Query
        $sqlQuery = "SELECT `user_id` AS `id`, `name` FROM " . $this->table;
        // Prepare Query
        $stmt = $this->conn->prepare($sqlQuery);
        // Execute Query
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
        return $stmt;
    }

    // User Vaibales Getters
    function get_id()
    {
        return $this->id;
    }

    function get_name()
    {
        return $this->name;
    }

    // Room Variables Setters
    function set_id($id)
    {
        if (is_numeric($id))
            $this->id = htmlspecialchars(strip_tags($id));
    }

    function set_name($name)
    {
        $this->name = htmlspecialchars(strip_tags($name));
    }
}
