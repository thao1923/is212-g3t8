<?php
class ConnectionManager {
    public function getConnection() {
        $servername = 'localhost';
        $dbname = 'spm_database'; //database name temporary
        $username = 'root';
        $password = '';
        
        $dsn  = "mysql:host=$servername;dbname=$dbname";
        return new PDO($dsn, $username, $password);  
    }
}
?>