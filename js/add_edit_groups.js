$(document).ready(function() {
        //hold group users
    var group_users = []
    
        //setup accordion
    $('#accordion_addedit_groups').accordion({
        icons: false,
        heightStyle: "content"
    }); 
    
        //setup multiselect
    $("#users_select").multiselect({
        noneSelectedText: "Select Users",
        minWidth: "300"
    });
    
        //setup buttons
    $('#add_btn').button({
        icons: {
            primary: "ui-icon-circle-plus"
        }
    }).click(function () {
        add_group();
    });

        //setup buttons
    $('#cancel_btn').button({
        icons: {
            primary: "ui-icon-circle-close"
        }
    }).click(function () {
        window.location = "../Views/groups.php";
    });
    
        //get group info if call is coming from edit group
    if ($('#groupid').text() != '') {
        get_group();
        get_users();
    } else {
        get_users();
    }
    
        //get group info in case is updating
    function get_group(){ 
        $.ajax({
            url: '../Controllers/group_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'get_group_info',
                groupid: $('#groupid').text()
            },
            success: function (data) {
                if (!data['group']) {
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
                    $('#groupname').val(data['group']['group_name']);
                    
                    for (i = 0; i < data['users'].length; i++) { 
                        group_users.push(data['users'][i]['id']);
                    }
                }
            }
        });
    }
    
        //add group
    function add_group() { 
        $.ajax({
            url: '../Controllers/group_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'add_update_group',
                groupid: $('#groupid').text(),
                groupname: $('#groupname').val(),
                users: $('#users_select').val()
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
                                        window.location = "../Views/groups.php";
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
    function get_users(){ 
        $.ajax({
            url: '../Controllers/user_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'get_all_users'
            },
            success: function (data) {
                if (data['users']) {
                    for (i = 0; i < data['users'].length; i++) {
                        if(jQuery.inArray(data['users'][i]['id'], group_users) !== -1){
                            $('#users_select').append($('<option></option>').attr('value', data['users'][i]['id']).attr('selected', 'selected').text(data['users'][i]['name']));
                        } else {
                            $('#users_select').append($('<option></option>').attr('value', data['users'][i]['id']).text(data['users'][i]['name']));
                        } 
                        $('#users_select').multiselect("refresh");
                    }
                }
            }
        });
    }
    
});

