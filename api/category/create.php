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
    // Get data of (category) from POST request
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->name)){
        $categoryObj -> set_name($data -> name);
        // Create post
        if($categoryObj -> create($errorMessage)){
            // set response code - 201 created
            http_response_code(201);
            // tell the user
            echo json_encode(array("message" => "Category was created."));
        } else{ // if unable to create the category, tell the user
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to create category. $errorMessage"));
        }
       } else { // // tell the user data is incomplete
            // set response code - 400 bad request
            http_response_code(400);
            // tell the user
            echo json_encode(array("message" => "Unable to create category. Data is incomplete."));
       }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/category/create.php
    // in header
    // Content-Type: application/json
    /*
        {
            "name" : "CategoryXYZ"
        }
    */
