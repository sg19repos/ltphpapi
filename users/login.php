<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    include_once '../config/database.php';
    include_once '../objects/users.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new Users($db);
    
    // set ID property of record to read
    // $user->userName = isset($_GET['userName']) ? $_GET['userName'] : die();
    // $user->userPassword = isset($_GET['userPassword']) ? $_GET['userPassword'] : die();
    
    $data = json_decode(file_get_contents("php://input"));
    if(
        !empty($data->userId) &&
        !empty($data->userPassword)
    ){
        $user->userId = $data->userId;
        $user->userPassword = $data->userPassword;

        $user->readOne();
        if($user->userId!=null){
            $product_arr = array(
                "userId" =>  $user->userId,
                "userFullName" => $user->userFullName
            );
        
            http_response_code(200);
            echo json_encode($product_arr);
        }
        else{
            http_response_code(404);
            echo json_encode(array("message" => "User does not exist."));
        }
    }
    else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to login user. Data is incomplete."));
    }
?>