<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    
    include_once '../objects/moods.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new Moods($db);

    $data = json_decode(file_get_contents("php://input"));
    
    // make sure data is not empty
    if(
        !empty($data->moodName) &&
        !empty($data->moodStartTime) &&
        !empty($data->moodUserId)
    ){
        $user->moodName = $data->moodName;
        $user->moodStartTime = $data->moodStartTime;
        $user->moodUserId = $data->moodUserId;
        
        if($user->create()){
            http_response_code(201);
            echo json_encode(array("message" => "Mood was successfully added."));
        }
        else{
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create mood."));
        }
    }
    else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create mood. Data is incomplete."));
    }
?>