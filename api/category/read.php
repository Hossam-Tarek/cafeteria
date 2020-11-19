<?php
require_once '../../config/database.php';
require_once '../../models/category.php';

header('Content-Type: application/json');

// instantiate Database object and connection
$db = new Database();
$conn = $db->connect();

$categoryObj = new Category($conn);
// get categories
$result = $categoryObj->readAll();

if ($result && $all_categories = $result->fetchAll(PDO::FETCH_ASSOC)) {
    // set response code - 200 OK
    http_response_code(200);
    echo json_encode($all_categories);
} else {
    // set response code - 404 Not found
    http_response_code(404);
    echo json_encode(array(
        "message" => 'No Categories found'
    ));
}
// Close connection
// $db->close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/category/read.php
