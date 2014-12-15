/*
 * Describe js for Sys Manager View (Index page)
 */
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
        window.location = window.location.href+"Views/users.php";           
    });
    
    $('#groups_btn').button({
        icons: {
            primary: "ui-icon-contact"
        },
        text: false
    }).click(function() {
        window.location = window.location.href+"Views/groups.php";
    });
});
