<?php
    require_once '../../config/database.php';
    require_once '../../models/order.php';
    
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: POST");

    // instantiate Database object and connection
    $db = new Database();
    $conn = $db -> connect();

    $oredrObj = new Order($conn);
    $errorMessage;
    // Get data of (Order) from POST request
    $data = json_decode(file_get_contents("php://input"));
    // print_r($data->status);die();
    if(!empty($data -> id) && isset($data -> status)){
        $oredrObj -> set_order_id($data -> id);
        $oredrObj -> set_status($data -> status);
        // Update order
        if($oredrObj -> update($errorMessage)){
            // set response code - 200 Ok
            http_response_code(200);
            // tell the user
            echo json_encode(array("message" => "Order was Updated."));
        } else{ // if unable to update the order, tell the user
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to update order. $errorMessage"));
        } 
    } else { // tell the user data is incomplete
        // set response code - 404 Not found
        http_response_code(404);
        // tell the user
        echo json_encode(array("message" => "Unable to update order. Data is incomplete or wrong."));
    }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/order/update.php
    // in header
    // Content-Type: application/json
    /*
        {
            "id" : "5",
            "status" : "0"
        }
    */
