<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    //header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');    
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
}   
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
} 
?>
<?php
    header("Access-Control-Allow-Origin: *");
    // header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    // header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    // header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/core.php';
    include_once '../config/database.php';
    include_once '../objects/users.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new Users($db);
    
    // $data = json_decode(file_get_contents("php://input"));
    // $data = parse_str(parse_url('localhost/lt_180519/lt_api/users/search.php/key=value2', PHP_URL_QUERY), $output);
    if (!isset($data)) {
        $data = new stdClass();
    }
    $data->success = false;

    $data->userId = htmlspecialchars($_GET["userId"]);

    $users_arr=array();
    $users_arr["users"]=array();

    if(!empty($data->userId)){
        $user->userId = $data->userId;
        $stmt = $user->searchUser();
        $num = $stmt->rowCount();
        
        if($num>0){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
                extract($row);
        
                $user_item=array(
                    "userFullName" => $lt_user_fullname,
                    "userRowId" => $lt_user_row_id
                );
        
                array_push($users_arr["users"], $user_item);
            }

            http_response_code(200);
            echo json_encode($users_arr);
        }
        
        else{
            $users_arr["message"]="No products found.";
            http_response_code(200);
            array_push($users_arr["users"]);
            echo json_encode($users_arr);
        }
    }
    else{
        $users_arr["message"]="Unable to search user. Data is incomplete.";
        http_response_code(200);
        array_push($users_arr["users"]);
        echo json_encode($users_arr);
    }
    
?>