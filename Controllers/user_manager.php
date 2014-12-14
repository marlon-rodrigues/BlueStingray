<?php
if (!isset($_SESSION)) {
    session_start();
}


date_default_timezone_set('UTC');

require_once($_SERVER["DOCUMENT_ROOT"] . '/Models/user_model.php');
$function_name = $_POST['function_name'];
$username = (isset($_POST['username'])) ? $_POST['username'] : '';
$userid = (isset($_POST['userid'])) ? $_POST['userid'] : '';
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
        $return = get_user_info($userid_edit);
        break;
    case 'add_update_user':
        $return = add_update_user($userid_edit, $username);
        break;
    case 'delete_user':
        $return = delete_user($userid_edit);
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

function get_user_info($user_id){
    $return_func['user'] = true;
    $return_func['message'] = '';
    $login_model = new Login_Model();
    $user = $login_model->get_user_info($user_id);
    if (!$user) {
        $return_func['user'] = false;
        $return_func['message'] = 'There was an error retrieving users. Please contact Administrator.';
    } else {
        $return_func['user'] = $user;
    }
    return $return_func;   
}

function add_update_user($userid, $username, $email, $password, $super_admin){
    $return_func['success'] = true;
    $return_func['message'] = 'Save was successful';
    $login_model = new Login_Model();
    
    //validate fields
    if ($username == '' || $username == NULL || trim($username) == ''){
        $return_func['success'] = false;
        $return_func['message'] = 'Please provide user name.';
        return $return_func;
    }
    
    if ($password == '' || $password == NULL || trim($password) == ''){
        $return_func['success'] = false;
        $return_func['message'] = 'Please provide password.';
        return $return_func;
    }
    
    //if is a new user, verify if already exists
    if ($userid < 0 || $userid == ''){
         if($login_model->validate_user($username)){
             $return_func['success'] = false;
             $return_func['message'] = 'User name alrady exists.';
             return $return_func;
         } 
    }
    
    //encript password
    $psw = md5($password);
    
    //add/update user
    if(!$login_model->add_update_user($username, $email, $psw, $super_admin, $userid)){
        $return_func['success'] = false;
        $return_func['message'] = 'There was an error saving user. Please contact Administrator.';
    }
    
    return $return_func;   
}

function delete_user($userid){
    $return_func['success'] = true;
    $return_func['message'] = 'Delete was successful';
    $login_model = new Login_Model();
    
    $del_user = $login_model->delete_user($userid);
    
    if(!$del_user){
        $return_func['success'] = false;
        $return_func['message'] = 'There was an error deleting user. Please contact Administrator.';
    }
    
    return $return_func; 
}
?>