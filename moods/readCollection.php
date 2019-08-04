<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    include_once '../config/database.php';
    include_once '../objects/collections.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $collection = new Collections($db);
    
    // $data = json_decode(file_get_contents("php://input"));
    if (!isset($data)) {
        $data = new stdClass();
    }
    $data->success = false;

    $data->moodId = htmlspecialchars($_GET["moodId"]);

    $collections_arr=array();
    $collections_arr["collections"]=array();

    // $collection->moodId = $data->moodId;

    // $collection->fetchMoodCollections();
    // if($collection->moodId!=null){
    //     $moods_arr = array(
    //         "moodId" =>  $collection->moodId,
    //         "moodTrackName" => $collection->moodTrackName
    //     );
    
    //     http_response_code(200);
    //     echo json_encode($moods_arr);
    // }
    
    if(!empty($data->moodId)){
        $collection->moodId = $data->moodId;
        $stmt = $collection->fetchMoodCollections();
        $num = $stmt->rowCount();
        
        if($num>0){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
                extract($row);
        
                $collection_item=array(
                    "moodId" => $lt_mood_id,
                    "moodTrackName" => $lt_mood_track
                );
        
                array_push($collections_arr["collections"], $collection_item);
            }

            http_response_code(200);
            echo json_encode($collections_arr);
        }
        
        else{
            $collections_arr["message"]="No products found.";
            http_response_code(200);
            array_push($collections_arr["collections"]);
            echo json_encode($collections_arr);
        }
    }


    else{
        $users_arr["message"]="Unable to fetch collecctions. Data is incomplete.";
        http_response_code(200);
        array_push($collections_arr["collections"]);
        echo json_encode($collections_arr);
    }

?>