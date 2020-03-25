<?php
   require_once "common.php";

    class adminDAO{
    
        public function retrieveAll(){
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection();

            $sql = "select * from admin"; 
            $stmt = $pdo->prepare($sql);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $results = [];
                //step 4 - retrieve query results (if any)
            while($row = $stmt->fetch()){
                $results[] = new admin($row['userid'],$row['password']);
            }


            //step 5 - clear resources $stmt, $pdo
            $stmt = null;
            $pdo = null;

            //step 6 - return (if any)
            return $results;
        }



        public function getUserID($userid) {
            $connMgr = new ConnectionManager();
            $conn = $connMgr->getConnection();
    
            $sql = "select * from admin where userid = :userid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
 
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $admin = null;
            if( $row = $stmt->fetch() ) {
                $admin = new Admin($row['userid'],$row['password']);
                        
            }

            $stmt = null;
            $conn = null;
  
            return $admin;
        }

        public function authenticate($userid, $password) {
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection();
            
            // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
            $sql = "select * 
            from admin 
            where userid = :userid";
    
        
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(':userid', $userid, PDO::PARAM_STR);
    
          
            
            // Step 3 - Execute SQL Query
            
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $status = $stmt -> execute();
    
            if(!$status){
                // if $status == false
                $err = $stmt-> errorinfo();
                var_dump($err);
            }
    
            $return_msg = '';
     
            // Step 4 - Retrieve Query Results (if any)
            if ($row = $stmt -> fetch()){
                
                // $row['pass'] -> hashed
                // yesss sth found
                
                if( password_verify( $password, $row['password'] )){
                    $return_msg = 'SUCCESS';
                    
                }else{
                    $return_msg = 'Password is incorrect';
                }
            }
            else{
                $return_msg = 'Username is incorrect';
            }
            
            // Step 5 - Clear Resources $stmt, $pdo
            $stmt = null;
            $pdo = null;
    
    
            // Step 6 - Return (if any)
            return $return_msg;
        }
           
        

    }
?>