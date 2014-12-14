<?php
if (!isset($_SESSION)) {
    session_start();
}


date_default_timezone_set('UTC');

require_once($_SERVER["DOCUMENT_ROOT"] . '/Models/group_model.php');
$function_name = $_POST['function_name'];
$groupname = (isset($_POST['groupname'])) ? $_POST['groupname'] : '';
$groupid = (isset($_POST['groupid'])) ? $_POST['groupid'] : '';
$return = array();
/*
 * Redirects to the right function based on the "funciton name" passed
 * @author: Marlon Rodrigues
 * @return: json object with group information
 */
switch ($function_name) {
    case 'get_all_groups': 
        $return = get_all_groups();
        break;
    case 'get_group_info':
        $return = get_group_info($groupid);
        break;
    case 'add_update_group':
        $return = add_update_group($groupid, $groupname);
        break;
    case 'delete_group':
        $return = delete_group($groupid);
        break;
}
echo json_encode($return);

/*
 * Get a list of all groups in the db
 * @author: Marlon Rodrigues
 * @return: array with groups or a error message
 */
function get_all_groups() {
    $return_func['groups'] = true;
    $return_func['message'] = '';
    
    $group_model = new Group_Model();
    $groups = $group_model->get_all_groups();
    
    if ($groups < 0) {
        $return_func['groups'] = false;
        $return_func['message'] = 'There was an error retrieving groups. Please contact Administrator.';
    } else {   
        $return_func['groups'] = $groups;
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