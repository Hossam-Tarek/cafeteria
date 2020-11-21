<?php
    require_once '../../config/database.php';
    require_once '../../models/product.php';
    
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: POST");

    // instantiate Database object and connection
    $db = new Database();
    $conn = $db -> connect();

    $productObj = new Product($conn);
    $errorMessage;
    // Get data of (post) from POST request
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->name) && !empty($data->price) && !empty($data->image) && !empty($data->category_id) && !empty($data->id)){
        $productObj -> set_name($data -> name);
        $productObj -> set_price($data -> price);
        $productObj -> set_image($data -> image);
        $productObj -> set_category_id($data -> category_id);
        $productObj -> set_product_id($data -> id);
        // Update post
        if($productObj -> update($errorMessage)){
            // set response code - 200 Ok
            http_response_code(200);
            // tell the user
            echo json_encode(array("message" => "Product was Updated."));
        } else{ // if unable to update the product, tell the user
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to update product. $errorMessage"));
        }
       } else { // // tell the user data is incomplete
            // set response code - 400 bad request
            http_response_code(400);
            // tell the user
            echo json_encode(array("message" => "Unable to update product. Data is incomplete."));
       }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/product/update.php
    // in header
    // Content-Type: application/json
    /*
       {
            "id" : "37",
            "name" : "ProductXYZ Updated",
            "price" :  "20.25",
            "image" : "ProuctXYZ_Updated.png",
            "category_id" : "4"
        }
    */
