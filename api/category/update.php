<?php
    require_once '../../config/database.php';
    require_once '../../models/category.php';
    
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, 
    Access-Control-Allow-Headers, 
    Access-Control-Allow-Methods, 
    Authorization, 
    X-Requested-With");
 
    // instantiate Database object and connection
    $db = new Database();
    $conn = $db -> connect();

    $categoryObj = new Category($conn);
    $errorMessage;
    // Get data of (Category) from POST request
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->id) && !empty($data->name)){
        $categoryObj -> set_category_id($data -> id);
        $categoryObj -> set_name($data -> name);
        // Update category
        if($categoryObj -> update($errorMessage)){
            // set response code - 200 Ok
            http_response_code(200);
            // tell the user
            echo json_encode(array("message" => "Category was Updated."));
        } else{ // if unable to update the category, tell the user
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to update category. $errorMessage"));
        }
       } else { // // tell the user data is incomplete
            // set response code - 400 bad request
            http_response_code(400);
            // tell the user
            echo json_encode(array("message" => "Unable to update category. Data is incomplete."));
       }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/category/update.php
    // in header
    // Content-Type: application/json
    /*
       {
            "id" : "37",
            "name" : "CategoryXYZ Updated"
        }
    */
