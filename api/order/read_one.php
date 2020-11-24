<?php
    require_once '../../config/database.php';
    require_once '../../models/order.php';
    
    header('Content-Type: application/json');
    // instantiate Database object and connection
    $db = new Database();
    $conn = $db -> connect();

    $orderObj = new Order($conn);
    
    if(isset($_GET['id'])){
        $orderObj -> set_order_id($_GET['id']);
        // get result
        $result = $orderObj -> readOne();
        if($result) {
            // set response code - 200 OK
            http_response_code(200);
            echo json_encode($result);
        } else {
            // set response code - 404 Not found
            http_response_code(404);
            echo json_encode(array(
                "message" => 'Order does not exist'
            ));
        }
    } else {
        // set response code - 404 Not found
        http_response_code(404);
        echo json_encode(array(
            "message" => 'Order id not passed'
        ));
    }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/order/read_one.php?id=5
    