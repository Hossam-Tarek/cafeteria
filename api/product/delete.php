<?php
    require_once '../../config/database.php';
    require_once '../../models/product.php';
    
    header('Content-Type: application/json');
    // instantiate Database object and connection
    $db = new Database();
    $conn = $db -> connect();

    $productObj = new Product($conn);
    // Get data of (post) from POST request
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->id)){
        $productObj -> set_product_id($data->id);
        // Delete product
        $deletion_result = $productObj -> delete();
        if($deletion_result == 1) { // 1 if product deleted
            // set response code - 200 OK
            http_response_code(200);
            // tell the user
            echo json_encode(array("message" => "Product was deleted."));
        } elseif ($deletion_result == 2) {// if unable to delete the product, tell the user
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to delete product."));
        }
    } else { // tell the user data is incomplete
        // set response code - 404 Not found
        http_response_code(404);
        // tell the user
        echo json_encode(array("message" => "Unable to delete product. Data is incomplete or wrong."));
    }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/product/delete.php
    // in header
    // Content-Type: application/json
    /*
        {
            "id" : "5"
        }
    */
    