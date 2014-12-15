<?php
date_default_timezone_set('UTC');
require_once($_SERVER["DOCUMENT_ROOT"] . '/Models/DBConn.php');

class Group_Model extends DBConn {
    function __construct() {
        parent::__construct();
    }
    
    /*
    * Get a list of all groups in the db
    * @author: Marlon Rodrigues
    * @return: array with groups or a error message
    */
    function get_all_groups(){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        $sql = "SELECT * FROM groups ORDER by group_name";
       
        $result = $conn->query($sql);
       
        if($result){ 
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            return -1;
        }
    }
    
    /*
    * Get a single group in the db
    * @author: Marlon Rodrigues
    * @param: Int group_id - Group id been searched
    * @return: array with groups or a error message
    */
    function get_group_info($group_id){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        $sql = "SELECT * FROM groups WHERE id = " . $group_id;
       
        $result = $conn->query($sql);
       
        if($result){
            return mysqli_fetch_array($result, MYSQLI_ASSOC);
        } else {
            return -1;
        }
    }
    
    /*
    * Add/edit groups
    * @author: Marlon Rodrigues
    * @param: String groupname - Name of the group, Int groupid - Group id, Array users - list of users
    * @return: boolean
    */    
    function add_update_group($groupname, $groupid, $users){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        if($groupid < 0 || $groupid == ''){
            $sql = "INSERT INTO groups (group_name)
                    VALUES ('" . $groupname . "')"; 
            
            $result = $conn->query($sql);
                //get the last id inserted
            $groupid = mysqli_insert_id($conn);
        } else {
            $sql = "UPDATE groups SET group_name = '" . $groupname . "'
                    WHERE id = " . $groupid;         
            $result = $conn->query($sql);
        }
        
        if($result){
                //update users_group table
            $sql_del_group = "DELETE FROM users_group WHERE group_id = " . $groupid;
            $result_del_group = $conn->query($sql_del_group);
            
            if($result_del_group) {
                if(!empty($users)){
                    foreach($users as $user){
                        $sql_add_users = "INSERT INTO users_group (user_id, group_id)
                                          VALUES (" . $user . ", " . $groupid . ")"; 
                        $conn->query($sql_add_users);
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        
        return true;
    }
    
    /*
    * Verifies if the group alreday exists
    * @author: Marlon Rodrigues
    * @param: String groupname - Name of the group been validated, Int groupid - Group id been validated
    * @return: array with users or a error message
    */
    function validate_group($groupname, $groupid){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        $sql = "SELECT * FROM groups WHERE group_name = '" . $groupname . "' LIMIT 1";
       
        $result = $conn->query($sql);
       
        if($result){
            if($result->num_rows > 0){
                $group_row = mysqli_fetch_row($result);
                
                    //if it is editing a group validation return false
                if($group_row[0] == $groupid){
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /*
    * Delete group and its relation to users
    * @author: Marlon Rodrigues
    * @param: Int groupid Group id
    * @return: boolean
    */  
    function delete_group($groupid){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
            //delete all the associations with users
        $sql_del_assoc = "DELETE from users_group WHERE group_id = " . $groupid;
        $result_del = $conn->query($sql_del_assoc);
        
        if($result_del){
                //delete group
            $sql = "DELETE FROM groups WHERE id = " . $groupid;

            $result = $conn->query($sql);
            
            if($result){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /*
    * Get users associated with one group
    * @author: Marlon Rodrigues
    * @param: Int group_id - Group id been searched
    * @return: array with users or a error message
    */
    function get_group_users($group_id){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        $users = array();
        
        $sql = "SELECT user_id FROM users_group WHERE group_id = " . $group_id;
       
        $result = $conn->query($sql);
       
        if($result){
            while($user = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $sql_users = "SELECT * FROM users WHERE id = " . $user['user_id'];

                $result_users = $conn->query($sql_users);

                if ($result_users) {
                    $users_row = mysqli_fetch_row($result_users);

                    $users[] = array(
                        'id' => $users_row[0],
                        'name' => $users_row[1]
                    );
                }
            }

            return $users;
        } else {
            return -1;
        }
    }
}
?>