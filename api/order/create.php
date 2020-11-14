<?php
    require_once '../../config/database.php';
    require_once '../../models/order.php';
    
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

    $oredrObj = new Order($conn);
    $errorMessage;
    // Get data of (Order) from POST request
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->user) && !empty($data->room) && !empty($data->comment) && !empty($data->products) && is_array($data->products)){
        $oredrObj -> set_user_id($data -> user);
        $oredrObj -> set_room_id($data -> room);
        $oredrObj -> set_comment($data -> comment);
        $oredrObj -> set_products_id_quantity($data -> products);
        // Create order
        if($oredrObj -> create($errorMessage)){
            // set response code - 201 created
            http_response_code(201);
            // tell the user
            echo json_encode(array("message" => "Order was created."));
        } else{ // if unable to create the order, tell the user
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to create order. $errorMessage"));
        }
       } else { // // tell the user data is incomplete
            // set response code - 400 bad request
            http_response_code(400);
            // tell the user
            echo json_encode(array("message" => "Unable to create order. Data is incomplete."));
       }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/order/create.php
    // in header
    // Content-Type: application/json
    /*
       {
            "user" : "1",
            "room" :  "2",
            "comment" : "comment 1",
            "products" : [
                {
                    "id" : "1",
                    "quantity" : "3"
                },
                {
                    "id" : "5",
                    "quantity" : "1"
                }
            ]
        }
    */
