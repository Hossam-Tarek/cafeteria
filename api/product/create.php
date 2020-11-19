<?php
    require_once '../../config/database.php';
    require_once '../../models/product.php';
    
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

    $productObj = new Product($conn);
    $errorMessage;
    // Get data of (Product) from POST request
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->name) && !empty($data->price) && !empty($data->image) && !empty($data->category_id)){
        $productObj -> set_name($data -> name);
        $productObj -> set_price($data -> price);
        $productObj -> set_image($data -> image);
        $productObj -> set_category_id($data -> category_id);
        // Create product
        if($productObj -> create($errorMessage)){
            // set response code - 201 created
            http_response_code(201);
            // tell the user
            echo json_encode(array("message" => "Product was created."));
        } else{ // if unable to create the product, tell the user
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to create product.  $errorMessage"));
        }
       } else { // // tell the user data is incomplete
            // set response code - 400 bad request
            http_response_code(400);
            // tell the user
            echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
       }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/product/create.php
    // in header
    // Content-Type: application/json
    /*
       {
            "name" : "ProductXYZ",
            "price" :  "120.65",
            "image" : "ProuctXYZ.png",
            "category_id" : "6"
        }
    */
