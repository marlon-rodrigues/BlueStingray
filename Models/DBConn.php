<?php
class DBConn {
    private $dbconn;
    private $username;
    private $password;
    private $host;
    private $database;
    
    function __construct() {
        $this->username = "vanzopob_marlon";
        $this->password = "35617038";
        $this->host = "localhost";
        $this->database = "vanzopob_bluestingray";
    }
    
    function open_connection() {
        $this->dbconn = mysql_connect($this->host, $this->username, $this->password);
        $return = array();
        
        if(!$this->dbconn) {
            $return['message'] = "Failed to connect to MySql" . mysql_error();
            $return['status'] = '1';
        } else {
            $return['message'] = "Connected Succesfully";
            $return['status'] = '0';
            
            mysql_select_db($this->database, $this->dbconn);
        }
        
        return $return;
    }
    
    function close_connection() {
        mysql_close($this->dbconn);
        return true;
    }
    
    function get_dbconn(){
        return $this->dbconn;
    }
}
?>