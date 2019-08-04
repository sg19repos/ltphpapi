<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    include_once '../config/database.php';
    include_once '../objects/subscriptions.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $subscription = new Subscriptions($db);
    
    $data = json_decode(file_get_contents("php://input"));

    $subscription->userSubscriberId = $data->userSubscriberId;

    $subscription->fetchSubscriptions();
    if($subscription->userSubscriberId!=null){
        $subscriptions_arr = array(
            "userSubscriptions" =>  $subscription->userSubscriptions,
            "userSubscriptionDate" => $subscription->userSubscriptionDate
        );
    
        http_response_code(200);
        // echo sizeOf($subscriptions_arr);
        // if(sizeOf($subscriptions_arr)==0){
        //     // echo json_encode("No Subscriptions available for this user.");
        //     sizeOf($subscriptions_arr)
        // }
        // else {
            echo json_encode($subscriptions_arr);
        // }
    }
    else{
        http_response_code(404);
        echo json_encode(array("message" => "No Subscriptions available for this user."));
    }

?>