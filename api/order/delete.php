<?php
    require_once '../../config/database.php';
    require_once '../../models/order.php';
    
    header('Content-Type: application/json');
 
    // instantiate Database object and connection
    $db = new Database();
    $conn = $db -> connect();

    $oredrObj = new Order($conn);
    $errorMessage;
    // Get data of (Order) from POST request
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->id)){
        $oredrObj -> set_order_id($data -> id);
        // Delete order
        $deletion_result = $oredrObj -> delete();
        if($deletion_result == 1) { // 1 if order deleted
            // set response code - 200 OK
            http_response_code(200);
            // tell the user
            echo json_encode(array("message" => "Order was deleted."));
        } elseif ($deletion_result == 2) {// if unable to delete the order, tell the user
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to delete order."));
        }
       } else { // tell the user data is incomplete
        // set response code - 404 Not found
        http_response_code(404);
        // tell the user
        echo json_encode(array("message" => "Unable to delete order. Data is incomplete or wrong."));
    }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/order/delete.php
    // in header
    // Content-Type: application/json
    /*
        {
            "id" : "5"
        }
    */
