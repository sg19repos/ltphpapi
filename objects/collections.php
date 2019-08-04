<?php
    class Collections{
    
        private $conn;
        private $table_name = "lt_moods_collection";
    
        public $moodId;
        public $moodTrack;

        public function __construct($db){
            $this->conn = $db;
        }
        
        // add mood track
        function addCollection(){
        
            $query = "INSERT INTO " . $this->table_name . "
                    SET
                    lt_mood_id=:lt_mood_id,lt_mood_track=:lt_mood_track";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->moodId=htmlspecialchars(strip_tags($this->moodId));
            $this->moodTrack=htmlspecialchars(strip_tags($this->moodTrack));
        
            // bind values
            $stmt->bindParam(":lt_mood_id", $this->moodId);
            $stmt->bindParam(":lt_mood_track", $this->moodTrack);
        
            // execute query
            if($stmt->execute()){
                return true;
            }
        
            return false;
            
        }
        
        // used when filling up the update product form
        function fetchMoodCollections(){
            // $query = "SELECT * FROM " . $this->table_name . " WHERE lt_mood_id=:lt_mood_id";
            $query = "SELECT lt_mood_id, lt_mood_track  FROM " . $this->table_name . " WHERE lt_mood_id=:lt_mood_id";

            $stmt = $this->conn->prepare( $query );

            // sanitize
            $this->moodId=htmlspecialchars(strip_tags($this->moodId));

            // bind values
            $stmt->bindParam(":lt_mood_id", $this->moodId);
        
            $stmt->execute();
            return $stmt;
            // $collections_arr=array();
            // $collections_arr["records"]=array();
        
            // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            //     extract($row);
        
            //     $collection_item=array(
            //         "moodId" => $lt_mood_id,
            //         "moodTrackName" => $lt_mood_track
            //     );
        
            //     array_push($collections_arr["records"], $collection_item);
            // }
            // http_response_code(200);

            // echo json_encode($collections_arr);
        }

        
        // used when filling up the update product form
        function readOne(){
        
            // query to read single record
            $query = "SELECT
                        user_id, user_name
                    FROM
                        " . $this->table_name . "
                    WHERE
                    user_name =:user_name AND user_password =:user_password";
        
            // prepare query statement
            $stmt = $this->conn->prepare( $query );

            // sanitize
            $this->userName=htmlspecialchars(strip_tags($this->userName));
            $this->userPassword=htmlspecialchars(strip_tags($this->userPassword));

            // bind values
            $stmt->bindParam(":user_name", $this->userName);
            $stmt->bindParam(":user_password", $this->userPassword);
        
             $stmt->execute();
             $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
             $this->userName = $row['user_name'];
             $this->userId = $row['user_id'];
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