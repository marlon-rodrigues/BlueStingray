<?php
date_default_timezone_set('UTC');
require_once($_SERVER["DOCUMENT_ROOT"] . '/Models/DBConn.php');

class User_Model extends DBConn {
    function __construct() {
        parent::__construct();
    }
    
    /*
    * Get a list of all users in the db
    * @author: Marlon Rodrigues
    * @return: array with users or a error message
    */
    function get_all_users(){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        $users_array = array();
        
        $sql = "SELECT * FROM users ORDER by name";
       
        $result = $conn->query($sql);
       
        if($result){
            $users =  mysqli_fetch_all($result, MYSQLI_ASSOC);
            
                //get groups
            if(!empty($users)){
                foreach($users as $user){
                    $sql_assoc = "SELECT group_id FROM users_group WHERE user_id = " . $user['id'];
                    $result_assoc = $conn->query($sql_assoc);

                    if($result_assoc){
                        $groups = mysqli_fetch_all($result_assoc, MYSQLI_ASSOC);
                        $group_names = '';

                        if(!empty($groups)){
                            foreach($result_assoc as $group){
                                $sql_groups = "SELECT group_name FROM groups WHERE id  = " . $group['group_id'];
                                $result_groups = $conn->query($sql_groups);

                                if($result_groups){
                                    $selected_groups = mysqli_fetch_row($result_groups);
                                    $group_names .= $selected_groups[0] . ', ';
                                }
                            }

                            $users_array[] = array(
                                'id' => $user['id'],
                                'name' => $user['name'],
                                'groups' => substr($group_names,0,-2)
                            );
                        } else {
                            $users_array[] = array(
                                'id' => $user['id'],
                                'name' => $user['name'],
                                'groups' => 'No Group Selected'
                            );
                        }
                    } else {
                        $users_array[] = array(
                            'id' => $user['id'],
                            'name' => $user['name'],
                            'groups' => 'No Group Selected'
                        );
                    }
                }
            }
            
            return $users_array;
        } else {
            return -1;
        }
    }
    
    /*
    * Get a single user in the db
    * @author: Marlon Rodrigues
    * @param: Int user_id - User id been searched
    * @return: array with user info or a error message
    */
    function get_user_info($user_id){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        $sql = "SELECT * FROM users WHERE id = " . $user_id;
       
        $result = $conn->query($sql);
       
        if($result){
            return mysqli_fetch_array($result, MYSQLI_ASSOC);
        } else {
            return -1;
        }
    }
    
    /*
    * Add/edit users
    * @author: Marlon Rodrigues
    * @param: String username - Name of the user, Int userid - User id, Array groups - list of groups
    * @return: boolean
    */    
    function add_update_user($username, $userid, $groups){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        if($userid < 0 || $userid == ''){
            $sql = "INSERT INTO users (name)
                    VALUES ('" . $username . "')"; 
            
            $result = $conn->query($sql);
                //get the last id inserted
            $userid = mysqli_insert_id($conn);
        } else {
            $sql = "UPDATE users SET name = '" . $username . "'
                    WHERE id = " . $userid;         
            $result = $conn->query($sql);
        }
        
        if($result){
                //update users_group table
            $sql_del_user = "DELETE FROM users_group WHERE user_id = " . $userid;
            $result_del_user = $conn->query($sql_del_user);
            
            if($result_del_user) {
                if(!empty($groups)){
                    foreach($groups as $group){
                        $sql_add_groups = "INSERT INTO users_group (user_id, group_id)
                                          VALUES (" . $userid . ", " . $group . ")"; 
                        $conn->query($sql_add_groups);
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
    * Verifies if the user alreday exists
    * @author: Marlon Rodrigues
    * @param: String username - Name of the user been validated, Int userid - User id been validated
    * @return: array with users or a error message
    */
    function validate_user($username, $userid){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        $sql = "SELECT * FROM users WHERE name = '" . $username . "' LIMIT 1";
       
        $result = $conn->query($sql);
       
        if($result){
            if($result->num_rows > 0){
                $user_row = mysqli_fetch_row($result);
                
                    //if it is editing a user validation return false
                if($user_row[0] == $userid){
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
    * Delete user
    * @author: Marlon Rodrigues
    * @param: Int userid User id
    * @return: boolean
    */  
    function delete_user($user_id){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        $sql = "DELETE FROM users WHERE id = " . $user_id;
       
        $result = $conn->query($sql);
       
        if($result){
            return true;
        } else {
            return false;
        }
    }
    
    /*
    * Get groups associated with one user
    * @author: Marlon Rodrigues
    * @param: Int user_id - User id been searched
    * @return: array with groups or a error message
    */
    function get_user_groups($user_id){
        $conn_status = $this->open_connection();
        $conn = $this->get_dbconn();
    
        if($conn_status['status'] == '1'){
            return false;
        }
        
        $groups = array();
        
        $sql = "SELECT group_id FROM users_group WHERE user_id = " . $user_id;
        $result = $conn->query($sql);
       
        if($result){
            while($group = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $sql_groups = "SELECT * FROM groups WHERE id = " . $group['group_id'];

                $result_groups = $conn->query($sql_groups);

                if ($result_groups) {
                    $groups_row = mysqli_fetch_row($result_groups);

                    $groups[] = array(
                        'id' => $groups_row[0],
                        'name' => $groups_row[1]
                    );
                }
            }

            return $groups;
        } else {
            return -1;
        }
    }
    
}
?>