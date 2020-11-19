<?php
require_once '../../config/database.php';
require_once '../../models/room.php';

header('Content-Type: application/json');

// instantiate Database object and connection
$db = new Database();
$conn = $db->connect();

$roomObj = new Room($conn);
// get rooms
$result = $roomObj->readAll();

if ($result && $all_rooms = $result->fetchAll(PDO::FETCH_ASSOC)) {
    // set response code - 200 OK
    http_response_code(200);
    echo json_encode($all_rooms);
} else {
    // set response code - 404 Not found
    http_response_code(404);
    echo json_encode(array(
        "message" => 'No products found'
    ));
}
// Close connection
// $db->close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/room/read.php
