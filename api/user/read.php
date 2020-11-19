<?php
require_once '../../config/database.php';
require_once '../../models/user.php';

header('Content-Type: application/json');

// instantiate Database object and connection
$db = new Database();
$conn = $db->connect();

$userObj = new User($conn);
// get rooms
$result = $userObj->readAll();

if ($result && $all_users = $result->fetchAll(PDO::FETCH_ASSOC)) {
    // set response code - 200 OK
    http_response_code(200);
    echo json_encode($all_users);
} else {
    // set response code - 404 Not found
    http_response_code(404);
    echo json_encode(array(
        "message" => 'No users found'
    ));
}
// Close connection
// $db->close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/user/read.php
