
<?php
require_once 'model/common.php';

#set errors

$error = [];


    
if (isset($_POST['username']) && isset($_POST['password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if($username == 'admin'){
        if ($password == 'Hello123'){
            $_SESSION['username'] = $username;
            header('Location: ../bootstrap/bootstrap.php');
            return;
        }else{
            $_SESSION['error'] = 'Password is incorrect';
            header('Location:login.php');
            return;
        }
        // $dao = new adminDAO();
        // $status = $dao -> authenticate($username, $password);
        

        // if ($status == 'SUCCESS'){
      
        //     $_SESSION['username'] = $username;
        //     header('Location: adminpage.php');
        //     return;
        // }else{
           
        //     $_SESSION['error'] = $status;
        //     header('Location:login.php');
        //     return;
        // }
    
        }else{
            $dao = new studentDAO();
            $status = $dao -> authenticate($username, $password);

            if ($status == 'SUCCESS'){
                
                $_SESSION['username'] = $username;
                header('Location: studentpage.php');
                return;
            }else{
               
                $_SESSION['error'] = $status;
                header('Location:login.php');
                return;
            }

    
        }

}





?>