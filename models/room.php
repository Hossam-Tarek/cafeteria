<?php
class Room
{

    // Database variables
    private $conn;
    private $table = 'Room';

    // Room variables
    private $room_id;
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
        $sqlQuery = "SELECT `room_id` AS `id`, `name` FROM " . $this->table;
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

    // Read one
    public function readOne()
    {
    }

    // Create room
    public function create(&$errorMessage)
    {
    }

    // Delete room
    public function delete()
    {
    }


    // Product Vaibales Getters
    function get_room_id()
    {
        return $this->room_id;
    }

    function get_name()
    {
        return $this->name;
    }

    // Product Variables Setters
    function set_room_id($room_id)
    {
        if (is_numeric($room_id))
            $this->product_id = htmlspecialchars(strip_tags($room_id));
    }

    function set_name($name)
    {
        $this->name = htmlspecialchars(strip_tags($name));
    }
}
