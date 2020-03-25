<?php
    require_once "common.php";

    class studentDAO{
        
       //retrieve all
     //  retrieve  by username
        public function retrieveAll(){

            //step 1 - connect to database
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection();

            //step 2 - write & prepare SQL Query (take care of param binding if neccesary)
            $sql = "SELECT * FROM student ";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            //step 3 - Execute SQL query
            $results = [];
                //step 4 - retrieve query results (if any)
            while($row = $stmt->fetch() ){
                $results[] = new student($row['userid'],
                                    $row['password'],
                                    $row['name'],
                                    $row['school'],
                                    $row['edollar']);
            }


            //step 5 - clear resources $stmt, $pdo
            $stmt = null;
            $pdo = null;

            //step 6 - return (if any)
            return $results;

        }
        public function getUserID($userid){

            //step 1 - connect to database
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection();

            //step 2 - write & prepare sql query
            $sql = "SELECT * FROM student WHERE userid = :userid";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            //step 3 - execute sql query 

            $results = null;
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            if($row = $stmt->fetch()) {
                $results = new Student($row['userid'], $row['password'], $row['name'], $row['school'],$row['edollar']);
    
            }
            $stmt = null;
            $pdo = null;
            return $results;
    
        }
        public function authenticate($userid, $password) {
            $conn_manager = new ConnectionManager();
            $pdo = $conn_manager->getConnection();
            
            // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
            $sql = "select * 
            from student 
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
                
                if( $password == $row['password'] ){
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



    //    public function getHashedPassword($username){
    //         $conn_manager = new ConnectionManager();
    //         $pdo = $conn_manager->getConnection();
            
    //         $sql = "select * from student where userid= :userid";
    //         $stmt = $pdo->prepare($sql);
    //         $stmt->bindParam(":userid", $userid);
    //         $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
    //         $stmt->execute();
    //         if($row = $stmt->fetch()){
                
    //            $hashed_password = $row["hashed_password"];

    //         } else {
    //             $hashed_password = FALSE;
    //         }

    //         $stmt->closeCursor();
    //         $pdo = null;

    //         return $hashed_password;
    //     }
    
?>