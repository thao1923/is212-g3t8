<?php
    spl_autoload_register(
        function($class){
            require_once "$class.php";
        }
    );


#checks if session is started if not, start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }


// function printErrors() {
//     if(isset($_SESSION['errors'])){
//         echo "<ul id='errors' style='color:red;'>";
        
//         foreach ($_SESSION['errors'] as $value) {
//             echo "<li>" . $value . "</li>";
//         }
        
//         echo "</ul>";   
//         unset($_SESSION['errors']);
//     }   
// }


if (!function_exists('isMissingOrEmpty')){
    function isMissingOrEmpty($name) {
        if (!isset($_REQUEST[$name])) {
            return "$name cannot be empty";
        }
    
        // client did send the value over
        $value = $_REQUEST[$name];
        if (empty($value)) {
            return "$name cannot be empty";
        }
    }

}

if (!function_exists('isEmpty')){
    function isEmpty($var) {
        if (isset($var) && is_array($var))
            foreach ($var as $key => $value) {
                if (empty($value)) {
                   unset($var[$key]);
                }
            }
    
        if (empty($var))
         return TRUE;
        }
}




?>