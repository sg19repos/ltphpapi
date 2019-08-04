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
    
    $userMoods = new Moods($db);
    
    // $data = json_decode(file_get_contents("php://input"));
    if (!isset($data)) {
        $data = new stdClass();
    }
    $data->success = false;

    $data->moodUserId = htmlspecialchars($_GET["moodUserId"]);

    $userMoods_arr=array();
    $userMoods_arr["userMoods"]=array();

    if(!empty($data->moodUserId)){
        $userMoods->moodUserId = $data->moodUserId;

        $stmt = $userMoods->fetchUserMoodsList();
        $num  = $stmt->rowCount();
        
        if($num>0){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
        
                $userMoods_item=array(
                    "lt_mood_name" => $lt_mood_name,
                    "lt_mood_user_id" => $lt_mood_user_id,
                );
        
                array_push($userMoods_arr["userMoods"], $userMoods_item);
            }
            http_response_code(200);
            // echo $num;
            echo json_encode($userMoods_arr);

        }
        else{
            http_response_code(200);
            echo $num;
            // echo json_encode(array("message" => "No moods available."));
            array_push($userMoods_arr["userMoods"]);
            echo json_encode($userMoods_arr);
        }
    }
    else{
        http_response_code(200);
        echo json_encode(array("message" => "Unable to user moods. Data is incomplete."));
    }

?>