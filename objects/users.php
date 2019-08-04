<?php
    class Users{
    
        private $conn;
        private $table_name = "lt_users";
    
        public $userRowId;
        public $userFullName;
        public $userPassword;
        public $userId;
        public $userEmailId;
        public $userRegDateTime;
        public $userStatus;
    
        public function __construct($db){
            $this->conn = $db;
        }
        
        // register User
        function register(){
        
            $query = "INSERT INTO " . $this->table_name . "
                    SET
                    lt_user_fullname=:lt_user_fullname,lt_user_password=:lt_user_password, lt_user_email=:lt_user_email, lt_user_reg_date_time=:lt_user_reg_date_time";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->userFullName=htmlspecialchars(strip_tags($this->userFullName));
            $this->userPassword=htmlspecialchars(strip_tags($this->userPassword));
            $this->userEmailId=htmlspecialchars(strip_tags($this->userEmailId));
            $this->userRegDateTime=htmlspecialchars(strip_tags($this->userRegDateTime));
        
            // bind values
            $stmt->bindParam(":lt_user_fullname", $this->userFullName);
            $stmt->bindParam(":lt_user_password", $this->userPassword);
            $stmt->bindParam(":lt_user_email", $this->userEmailId);
            $stmt->bindParam(":lt_user_reg_date_time", $this->userRegDateTime);
        
            // execute query
            if($stmt->execute()){
                $updateQuery = "UPDATE " . $this->table_name . "
                SET
                lt_user_id=CONCAT(SUBSTR(lt_user_fullname, 1, 3), lt_user_row_id) ORDER BY lt_user_row_id DESC LIMIT 1";

                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->execute();
               
                return true;
            }
            return false;
            
        }

        
        // used when filling up the update product form
        function readOne(){
        
            // query to read single record
            $query = "SELECT lt_user_id, lt_user_fullname FROM " . $this->table_name . " WHERE
                    lt_user_id =:lt_user_id AND lt_user_password =:lt_user_password";
        

            // $query = "SELECT
            //          user_id, user_name
            //      FROM
            //          " . $this->table_name . "
            //      WHERE
            //          user_name ='Kygo' AND user_password ='121212'";
        

            // prepare query statement
            $stmt = $this->conn->prepare( $query );

            // sanitize
            $this->userId=htmlspecialchars(strip_tags($this->userId));
            $this->userPassword=htmlspecialchars(strip_tags($this->userPassword));

            // bind values
            $stmt->bindParam(":lt_user_id", $this->userId);
            $stmt->bindParam(":lt_user_password", $this->userPassword);
        
            // execute query
            // if($stmt->execute()){
            //     // get retrieved row
            //     $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            //     // set values to object properties
            //     $this->userId = $row['user_id'];
            //     $this->userName = $row['user_name'];
            // }

             // execute query
             $stmt->execute();
        
             // get retrieved row
             $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
             // set values to object properties
             $this->userId = $row['lt_user_id'];
             $this->userFullName = $row['lt_user_fullname'];
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

        public function searchUser(){
            /**
             *  1. Query
             *  2. stmt preparation
             *  3. Sanitize
             *  4. Bind values
             *  5. Execute query
             *  6. Fetch
             *  7. Set fetched to object values
             *  8. return if required
             */
            //  $query = "SELECT lt_user_fullname as lt_user_fullname FROM ".$this->table_name." WHERE lt_user_id=:lt_user_id";
             $query = "SELECT lt_user_fullname, lt_user_row_id FROM ".$this->table_name." WHERE lt_user_id LIKE :lt_user_id";

             $stmt = $this->conn->prepare($query);

            //sanitize
            $this->userId=htmlspecialchars(strip_tags($this->userId));

            //bind
            $userId = "%$this->userId%";
            // $stmt->bindParam(":lt_user_id", $this->userId);
            $stmt->bindParam(":lt_user_id", $userId);

            $stmt->execute();
            return $stmt;
           
        }
        
    }
?>