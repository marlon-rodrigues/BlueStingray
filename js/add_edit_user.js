/*
 * Describe js for Add/Edit Users View
 */
$(document).ready(function() {
        //hold user groups
    var user_groups = [];
    
        //setup accordion
    $('#accordion_addedit_users').accordion({
        icons: false,
        heightStyle: "content"
    }); 
    
        //setup multiselect
    $("#groups_select").multiselect({
        noneSelectedText: "Select Groups",
        minWidth: "300"
    });
    
        //setup buttons
    $('#add_btn').button({
        icons: {
            primary: "ui-icon-circle-plus"
        }
    }).click(function () {
        add_user();
    });

        //setup buttons
    $('#cancel_btn').button({
        icons: {
            primary: "ui-icon-circle-close"
        }
    }).click(function () {
        window.location = "../Views/users.php";
    });
    
        //get user info if call is coming from edit user
    if ($('#userid').text() != '') {
        get_user();
        get_groups();
    } else {
        get_groups();
    }
    
        //get user info in case is updating
    function get_user(){ 
        $.ajax({
            url: '../Controllers/user_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'get_user_info',
                userid: $('#userid').text()
            },
            success: function (data) {
                if (!data['user']) {
                    $('#dialog').dialog({
                        title: 'Notice',
                        buttons: [{
                                text: 'OK',
                                click: function () {
                                    $(this).dialog('close');
                                }
                            }]
                    }).html(data['message']);
                } else {
                    $('#username').val(data['user']['name']);
                        //create array with user groups
                    for (i = 0; i < data['groups'].length; i++) { 
                        user_groups.push(data['groups'][i]['id']);
                    }
                }
            }
        });
    }
    
        //add user
    function add_user() { 
        $.ajax({
            url: '../Controllers/user_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'add_update_user',
                userid: $('#userid').text(),
                username: $('#username').val(),
                groups: $('#groups_select').val()
            },
            success: function (data) {
                if (data['success']) {
                    $('#dialog').dialog({
                        title: 'Notice',
                        buttons: [{
                                text: 'OK',
                                click: function () {
                                    $(this).dialog('close');

                                    if (data['success']) {
                                        window.location = "../Views/users.php";
                                    }
                                }
                            }]
                    }).html(data['message']);
                } else {
                    $('#dialog').dialog({
                        title: 'Notice',
                        buttons: [{
                                text: 'OK',
                                click: function () {
                                    $(this).dialog('close');
                                }
                            }]
                    }).html(data['message']);
                }
            }
        });
    }
    
        //fill select box with users
    function get_groups(){ 
        $.ajax({
            url: '../Controllers/group_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'get_all_groups'
            },
            success: function (data) {
                if (data['groups']) {
                    for (i = 0; i < data['groups'].length; i++) { 
                            //if user is assigned to that group, mark as selected
                        if(jQuery.inArray(data['groups'][i]['id'], user_groups) !== -1){
                            $('#groups_select').append($('<option></option>').attr('value', data['groups'][i]['id']).attr('selected', 'selected').text(data['groups'][i]['group_name']));
                        } else {
                            $('#groups_select').append($('<option></option>').attr('value', data['groups'][i]['id']).text(data['groups'][i]['group_name']));
                        }
                        $('#groups_select').multiselect("refresh");
                    }
                }
            }
        });
    }
});

