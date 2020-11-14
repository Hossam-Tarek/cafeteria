<?php
    require_once '../../config/database.php';
    require_once '../../models/order.php';
    
    header('Content-Type: application/json');

    // instantiate Database object and connection
    $db = new Database();
    $conn = $db -> connect();

    $orderObj = new Order($conn);
    // Get data of (Order) from POST request
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data -> user)){
        // get orders
        // echo $data -> user; die();
        $orderObj -> set_user_id($data -> user);
        $result = $orderObj -> readAll();
        if($result && $all_orders = $result -> fetchAll(PDO::FETCH_ASSOC)) {
            
            // set response code - 200 OK
            http_response_code(200);
            echo json_encode($all_orders);
        } else {
            // set response code - 404 Not found
            http_response_code(404);
            echo json_encode(array(
                "message" => 'No orders found'
            ));
        }
       } else { // tell the user data is incomplete
        // set response code - 404 Not found
        http_response_code(404);
        // tell the user
        echo json_encode(array("message" => "Unable to get user orders. Data is incomplete or wrong."));
    }

    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/order/read.php
    // in header
    // Content-Type: application/json
    /*
        {
            "user" : "5"
        }
    */
    