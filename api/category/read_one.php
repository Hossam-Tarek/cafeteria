<?php
    require_once '../../config/database.php';
    require_once '../../models/category.php';
    
    header('Content-Type: application/json');
    // instantiate Database object and connection
    $db = new Database();
    $conn = $db -> connect();

    $categoryObj = new Category($conn);
    
    if(isset($_GET['id'])){
        $categoryObj -> set_category_id($_GET['id']);
        // get result
        $result = $categoryObj -> readOne();
        if($result) {
            // set response code - 200 OK
            http_response_code(200);
            echo json_encode($result);
        } else {
            // set response code - 404 Not found
            http_response_code(404);
            echo json_encode(array(
                "message" => 'Category does not exist'
            ));
        }
    } else {
        // set response code - 404 Not found
        http_response_code(404);
        echo json_encode(array(
            "message" => 'Category id not passed'
        ));
    }
    // Close connection
    $db -> close_connection();
    // Use this Api as --> http://localhost/cafeteria/api/category/read_one.php?id=5
    