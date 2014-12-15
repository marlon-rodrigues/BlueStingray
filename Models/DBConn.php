<?php
class DBConn {
    private $dbconn;
    private $username;
    private $password;
    private $host;
    private $database;
    
    function __construct() {
        $this->username = "root";
        $this->password = "35617038";
        $this->host = "localhost";
        $this->database = "bluestingray";
    }
    
    function open_connection() {
        $this->dbconn = new mysqli($this->host, $this->username, $this->password, $this->database);
        $return = array();
        if ($this->dbconn->connect_errno) {
            $return['message'] = "Failed to connect to MySql" . $this->dbconn->connect_error();
            $return['status'] = '1';
        } else {
            $return['message'] = "Connected Succesfully";
            $return['status'] = '0';
        }
        
        return $return;
    }
    
    function close_connection() {
        mysqli_close($this->dbconn);
        return true;
    }
    
    function get_dbconn(){
        return $this->dbconn;
    }
}
?>