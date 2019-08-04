<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    include_once '../config/database.php';
    include_once '../objects/moods.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $mood = new Moods($db);
    
    // $data = json_decode(file_get_contents("php://input"));

    $stmt = $mood->fetchMoodsList();
    $num = $mood->count();
    
    if($num>0){
        $moods_arr=array();
        $moods_arr["records"]=array();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
    
            $mood_item=array(
                "lt_mood_name" => $lt_mood_name,
                "lt_mood_start_time" => $lt_mood_start_time,
                "lt_mood_user_id" => $lt_mood_user_id,
            );
    
            array_push($moods_arr["records"], $mood_item);
        }
        http_response_code(200);

        echo json_encode($moods_arr);
    }
    else{
        http_response_code(404);
        echo json_encode(array("message" => "No moods available."));
    }

?>