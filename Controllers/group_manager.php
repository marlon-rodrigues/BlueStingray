<?php
if (!isset($_SESSION)) {
    session_start();
}


date_default_timezone_set('UTC');

require_once($_SERVER["DOCUMENT_ROOT"] . '/Models/group_model.php');
$function_name = $_POST['function_name'];
$groupname = (isset($_POST['groupname'])) ? $_POST['groupname'] : '';
$groupid = (isset($_POST['groupid'])) ? $_POST['groupid'] : '';
$users = (isset($_POST['users'])) ? $_POST['users'] : '';
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
        $return = add_update_group($groupid, $groupname, $users);
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

/*
 * Get a single group in the db
 * @author: Marlon Rodrigues
 * @param: Int group_id - Group id been searched
 * @return: array with group info or a error message
 */
function get_group_info($group_id){
    $return_func['group'] = true;
    $return_func['users'] = true;
    $return_func['message'] = '';
    
    $group_model = new Group_Model();
    
        //get groups
    $group = $group_model->get_group_info($group_id);
    
    if ($group < 0) {
        $return_func['group'] = false;
        $return_func['users'] = false;
        $return_func['message'] = 'There was an error retrieving group. Please contact Administrator.';
    } else {
        if(count($group) > 0){
                //get users
            $users = $group_model->get_group_users($group_id);
            
            if($users < 0){
                $return_func['group'] = false;
                $return_func['users'] = false;
                $return_func['message'] = 'There was an error retrieving users associated with the group. Please contact Administrator.';
            } else {
                $return_func['group'] = $group;
                $return_func['users'] = $users;
            }
        } else {
            $return_func['group'] = false;
            $return_func['users'] = false;
            $return_func['message'] = 'Group was not found. Please try again.';
        }   
    }
    
    return $return_func;   
}

/*
 * Add update groups and users related to it
 * @author: Marlon Rodrigues
 * @param: Int groupid - Group id been searched, String groupname - Name of group been saved, Array $users - list of users id associated with the group
 * @return: boolean
 */
function add_update_group($groupid, $groupname, $users){
    $return_func['success'] = true;
    $return_func['message'] = 'Save was successful';
    $group_model = new Group_Model();
    
    //validate fields
    if ($groupname == '' || $groupname == NULL || trim($groupname) == ''){
        $return_func['success'] = false;
        $return_func['message'] = 'Please enter Group Name.';
        return $return_func;
    }
    
    //verify if group already exists
    if($group_model->validate_group($groupname, $groupid)){
        $return_func['success'] = false;
        $return_func['message'] = 'Group name alrady exists.';
        return $return_func;
    }

    //add/update group
    if(!$group_model->add_update_group($groupname, $groupid, $users)){
        $return_func['success'] = false;
        $return_func['message'] = 'There was an error saving group. Please contact Administrator.';
    }
    
    return $return_func;   
}

/*
 * Delete a group and its relation with users
 * @author: Marlon Rodrigues
 * @param: Int groupid - Group id been deleted
 * @return: boolean
 */
function delete_group($groupid){
    $return_func['success'] = true;
    $return_func['message'] = 'Delete was successful';
    $group_model = new Group_Model();
    
    $del_group = $group_model->delete_group($groupid);
    
    if(!$del_group){
        $return_func['success'] = false;
        $return_func['message'] = 'There was an error deleting group. Please contact Administrator.';
    }
    
    return $return_func; 
}
?>