<?php
if (!isset($_SESSION)) {
    session_start();
}


date_default_timezone_set('UTC');

require_once($_SERVER["DOCUMENT_ROOT"] . '/Models/user_model.php');
$function_name = $_POST['function_name'];
$username = (isset($_POST['username'])) ? $_POST['username'] : '';
$userid = (isset($_POST['userid'])) ? $_POST['userid'] : '';
$groups = (isset($_POST['groups'])) ? $_POST['groups'] : '';
$return = array();
/*
 * Redirects to the right function based on the "funciton name" passed
 * @author: Marlon Rodrigues
 * @return: json object with users information
 */
switch ($function_name) {
    case 'get_all_users': 
        $return = get_all_users();
        break;
    case 'get_user_info':
        $return = get_user_info($userid);
        break;
    case 'add_update_user':
        $return = add_update_user($userid, $username, $groups);
        break;
    case 'delete_user':
        $return = delete_user($userid);
        break;
}
echo json_encode($return);

/*
 * Get a list of all users in the db
 * @author: Marlon Rodrigues
 * @return: array with users or a error message
 */
function get_all_users() {
    $return_func['users'] = true;
    $return_func['message'] = '';
    
    $user_model = new User_Model();
    $users = $user_model->get_all_users();
    
    if ($users < 0) {
        $return_func['users'] = false;
        $return_func['message'] = 'There was an error retrieving users. Please contact Administrator.';
    } else {   
        $return_func['users'] = $users;
    }
    
    return $return_func;
}

/*
 * Get a single user in the db
 * @author: Marlon Rodrigues
 * @param: Int user_id - User id been searched
 * @return: array with user info or a error message
 */
function get_user_info($user_id){
    $return_func['user'] = true;
    $return_func['groups'] = true;
    $return_func['message'] = '';
    
    $user_model = new User_Model();
    
        //get user
    $user = $user_model->get_user_info($user_id);
    
    if ($user < 0) {
        $return_func['user'] = false;
        $return_func['groups'] = false;
        $return_func['message'] = 'There was an error retrieving user. Please contact Administrator.';
    } else {
        if(count($user) > 0){
                //get groups
            $groups = $user_model->get_user_groups($user_id);
            
            if($groups < 0){
                $return_func['user'] = false;
                $return_func['groups'] = false;
                $return_func['message'] = 'There was an error retrieving groups associated with the user. Please contact Administrator.';
            } else {
                $return_func['user'] = $user;
                $return_func['groups'] = $groups;
            }
        } else {
            $return_func['user'] = false;
            $return_func['groups'] = false;
            $return_func['message'] = 'User was not found. Please try again.';
        }   
    }
    
    return $return_func;     
}

/*
 * Add update users and groups related to it
 * @author: Marlon Rodrigues
 * @param: Int userid - User id been searched, String username - Name of user been saved, Array groups - list of group ids associated with the user
 * @return: boolean
 */
function add_update_user($userid, $username, $groups){
    $return_func['success'] = true;
    $return_func['message'] = 'Save was successful';
    $user_model = new User_Model();
    
    //validate fields
    if ($username == '' || $username == NULL || trim($username) == ''){
        $return_func['success'] = false;
        $return_func['message'] = 'Please enter User Name.';
        return $return_func;
    }
    
    //verify if user already exists
    if($user_model->validate_user($username, $userid)){
        $return_func['success'] = false;
        $return_func['message'] = 'User name alrady exists.';
        return $return_func;
    }

    //add/update user
    if(!$user_model->add_update_user($username, $userid, $groups)){
        $return_func['success'] = false;
        $return_func['message'] = 'There was an error saving user. Please contact Administrator.';
    }
    
    return $return_func;   
}

/*
 * Delete a user
 * @author: Marlon Rodrigues
 * @param: Int userpid - User id been deleted
 * @return: boolean
 */
function delete_user($userid){
    $return_func['success'] = true;
    $return_func['message'] = 'Delete was successful';
    $user_model = new User_Model();
    
    $del_user = $user_model->delete_user($userid);
    
    if(!$del_user){
        $return_func['success'] = false;
        $return_func['message'] = 'There was an error deleting user. Please contact Administrator.';
    }
    
    return $return_func; 
}
?>