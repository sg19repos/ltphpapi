<?php
    class Moods{
    
        private $conn;
        private $table_name = "lt_moods";
    
        public $moodId;
        public $moodName;
        public $moodStartTime;
        public $moodUserId;
        
        public function __construct($db){
            $this->conn = $db;
        }
        
        // create mood
        function create(){
        
            $query = "INSERT INTO " . $this->table_name . "
                    SET
                    lt_mood_name=:lt_mood_name,lt_mood_start_time=:lt_mood_start_time, lt_mood_user_id=:lt_mood_user_id";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->moodName=htmlspecialchars(strip_tags($this->moodName));
            $this->moodStartTime=htmlspecialchars(strip_tags($this->moodStartTime));
            $this->moodUserId=htmlspecialchars(strip_tags($this->moodUserId));
        
            // bind values
            $stmt->bindParam(":lt_mood_name", $this->moodName);
            $stmt->bindParam(":lt_mood_start_time", $this->moodStartTime);
            $stmt->bindParam(":lt_mood_user_id", $this->moodUserId);
        
            // execute query
            if($stmt->execute()){
                return true;
            }
        
            return false;
            
        }

        function fetchMoodsList(){
            $query = "SELECT * FROM " . $this->table_name . "";

            $stmt = $this->conn->prepare( $query );

            $stmt->execute();
            return $stmt;
        }

        function fetchUserMoodsList(){
            $query = "SELECT * FROM " . $this->table_name . " WHERE lt_mood_user_id =:lt_mood_user_id";
            // $query = "SELECT * FROM " . $this->table_name . " WHERE lt_mood_user_id = 2";
            // $query = "SELECT * FROM " . $this->table_name . "";

            $stmt = $this->conn->prepare($query);

             // sanitize
             $this->moodUserId=htmlspecialchars(strip_tags($this->moodUserId));

             // bind values
            $stmt->bindParam(":lt_mood_user_id", $this->moodUserId);

            $stmt->execute();
            return $stmt;
        }

        // used for paging products
        public function count(){
            /*$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "
            WHERE 
                user_name=:user_name and user_password =:user_password";
        */

            $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name ;

            $stmt = $this->conn->prepare( $query );
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $row['total_rows'];
        }
        
    }
?>