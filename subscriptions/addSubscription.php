<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    
    include_once '../objects/subscriptions.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $subscription = new Subscriptions($db);

    $data = json_decode(file_get_contents("php://input"));
    
    // make sure data is not empty
    if(
        !empty($data->userSubscriberId) &&
        !empty($data->userSubscriptions) &&
        !empty($data->userSubscriptionDate)
    ){
        $subscription->userSubscriberId = $data->userSubscriberId;
        $subscription->userSubscriptions = $data->userSubscriptions;
        $subscription->userSubscriptionDate = $data->userSubscriptionDate;
        
        if($subscription->subscribe()){
            http_response_code(201);
            echo json_encode(array("message" => "Subscription was successfully added."));
        }
        else{
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create subscription."));
        }
    }
    else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create subscription. Data is incomplete."));
    }
?>