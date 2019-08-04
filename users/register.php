<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    
    include_once '../objects/users.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new Users($db);

    $data = json_decode(file_get_contents("php://input"));
    
    // make sure data is not empty
    if(
        !empty($data->userFirstName) &&
        !empty($data->userLastName) &&
        !empty($data->userPassword) &&
        !empty($data->userEmailId) &&
        !empty($data->userRegDateTime)
    ){
        $user->userFullName = $data->userFirstName." ".$data->userLastName;
        $user->userPassword = $data->userPassword;
        $user->userEmailId = $data->userEmailId;
        $user->userRegDateTime = $data->userRegDateTime;
        
        if($user->register()){
            http_response_code(201);
            // echo $user->register();
            echo json_encode(array("message" => "User was registered successfully."));
        }
        else{
            http_response_code(503);
            // echo $user->register();
            echo json_encode(array("message" => "Unable to register user."));
        }
    }
    else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to register user. Data is incomplete."));
    }
?>