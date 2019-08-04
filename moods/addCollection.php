<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    
    include_once '../objects/collections.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $collection = new Collections($db);

    $mood = json_decode(file_get_contents("php://input"));
    
    // make sure data is not empty
    if(
        !empty($mood->moodId) &&
        !empty($mood->moodTrack)
    ){
        $collection->moodId = $mood->moodId;
        $collection->moodTrack = $mood->moodTrack;
        
        if($collection->addCollection()){
            http_response_code(201);
            echo json_encode(array("message" => "Track was successfully added."));
        }
        else{
            http_response_code(503);
            echo json_encode(array("message" => "Unable to add track."));
        }
    }
    else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to add track. Data is incomplete."));
    }
?>