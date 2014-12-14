$(document).ready(function() {
    //setup accordion
    $('#accordion_sys_manager').accordion({
        icons: false,
        heightStyle: "content"
    });
       
    $('#users_btn').button({
        icons: {
            primary: "ui-icon-person"
        },
        text: false
    }).click(function() { 
        window.location = "../Controllers/User_Controller.php";           
    });
    
    $('#groups_btn').button({
        icons: {
            primary: "ui-icon-contact"
        },
        text: false
    }).click(function() {
        window.location = "../Controllers/Dig_List_Manager_Controller.php";
    });
});