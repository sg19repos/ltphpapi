<?php
    class Subscriptions{
    
        private $conn;
        private $table_name = "lt_user_subscriptions";
    
        public $userSubscriberId;
        public $userSubscriptions;
        public $userSubscriptionDate;
    
        public function __construct($db){
            $this->conn = $db;
        }
        
        // create subscription
        function subscribe(){
        
            $query = "INSERT INTO " . $this->table_name . "
                    SET
                    lt_user_subscriber_id=:lt_user_subscriber_id,lt_user_subscriptions=:lt_user_subscriptions,lt_user_subscription_date=:lt_user_subscription_date";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->userSubscriberId=htmlspecialchars(strip_tags($this->userSubscriberId));
            $this->userSubscriptions=htmlspecialchars(strip_tags($this->userSubscriptions));
            $this->userSubscriptionDate=htmlspecialchars(strip_tags($this->userSubscriptionDate));
        
            // bind values
            $stmt->bindParam(":lt_user_subscriber_id", $this->userSubscriberId);
            $stmt->bindParam(":lt_user_subscriptions", $this->userSubscriptions);
            $stmt->bindParam(":lt_user_subscription_date", $this->userSubscriptionDate);
        
            // execute query
            if($stmt->execute()){
                return true;
            }

            return false;   
        }
        
        // used when filling up the update product form
        function fetchSubscriptions(){
            $query = "SELECT * FROM " . $this->table_name . " WHERE
            lt_user_subscriber_id =:lt_user_subscriber_id";

            $stmt = $this->conn->prepare( $query );

            // sanitize
            $this->userSubscriberId=htmlspecialchars(strip_tags($this->userSubscriberId));

            // bind values
            $stmt->bindParam(":lt_user_subscriber_id", $this->userSubscriberId);
        
            $stmt->execute();
        
            $subscriptions_arr=array();
            $subscriptions_arr["records"]=array();
        
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $subscription_item=array(
                    "userSubscriptions" => $lt_user_subscriptions,
                    "userSubscriptionDate" => $lt_user_subscription_date
                );
        
                array_push($subscriptions_arr["records"], $subscription_item);
            }
            http_response_code(200);

            echo json_encode($subscriptions_arr);
        }
        
        // used for paging products
        public function count(){
            $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "
            WHERE 
                user_name=:user_name and user_password =:user_password";
        
            $stmt = $this->conn->prepare( $query );
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $row['total_rows'];
        }
        
    }
?>