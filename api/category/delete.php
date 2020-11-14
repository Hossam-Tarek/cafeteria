<?php
    require_once '../../config/database.php';
    require_once '../../models/category.php';
    
    header('Content-Type: application/json');
    // instantiate Database object and connection
    $db = new Database();
    $conn = $db -> connect();

    $categoryObj = new Category($conn);
    // Get data of (Category) from POST request
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->id)){
        $categoryObj -> set_category_id($data->id);
        // Delete category
        $deletion_result = $categoryObj -> delete();
        if($deletion_result == 1) { // 1 if category deleted
            // set response code - 200 OK
            http_response_code(200);
            // tell the user
            echo json_encode(array("message" => "Category was deleted."));
        } elseif ($deletion_result == 2) {// if unable to delete the category, tell the user
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to delete category."));
        }
    } else { // tell the user data is incomplete
        // set response code - 404 Not found
        http_response_code(404);
        // tell the user
        echo json_encode(array("message" => "Unable to delete category. Data is incomplete or wrong."));
    }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/category/delete.php
    // in header
    // Content-Type: application/json
    /*
        {
            "id" : "5"
        }
    */
    