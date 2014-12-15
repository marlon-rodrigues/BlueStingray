$(document).ready(function() {
        //setup accordion
    $('#accordion_users').accordion({
        icons: false,
        heightStyle: "content"
    });
    
        //setup datatable
    var oTable = $('#user_table').dataTable({
        //"sDom": 'T<"clear">lfrtip',
        "sDom": '<"H"lfr>t<"F"ip>',
        "bLengthChange": false,
        "bProcessing": true,
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "sScrollX": "100%",
        "sScrollY": "200px",
        "iDisplayLength": 5,
        "oLanguage": {
            "sSearch": "Search:",
            "sEmptyTable": "There are no users in the system"
        },
        "bDeferRender": true,
        "bAutoWidth": false,
        "bPaginate": true,
        "aoColumnDefs": [
            {"asSorting": ["asc"], "aTargets": [1]},
            {"bSortable": false, "aTargets": [3]},
            {"bVisible": false, "aTargets": [0]}
        ],
        "fnDrawCallback": function (oSettings) {
            $('.edit_user').button({
                icons: {
                    primary: "ui-icon-pencil"
                },
                text: false
            }).unbind('click').bind('click', function () {
                $('#userid').val($(this).attr('id'));
                $('#username').val($(this).parent().prev().prev().html());
                $('#addedit_form').submit();
            });

            $('.delete_user').button({
                icons: {
                    primary: "ui-icon-close"
                },
                text: false
            }).unbind('click').bind('click', function () {
                var id = $(this).attr('id');
                var row = $(this).closest('tr').get(0);
                $('#dialog').dialog({
                    title: 'Delete',
                    buttons: [
                        {
                            text: 'YES',
                            click: function () {
                                $(this).dialog('close');
                                delete_user(id, row);
                            }
                        },
                        {
                            text: 'NO',
                            click: function () {
                                $(this).dialog('close');
                            }
                        }]
                }).html('Are you sure you want to delete user: ' + $(this).parent().prev().prev().html() + ' ?');
            });
        }
    });

    //setup buttons
    $('#add_btn').button({
        icons: {
            primary: "ui-icon-circle-plus"
        }
    }).click(function () {
        $('#userid').val('');
        $('#username').val('');
        $('#addedit_form').submit();
    });

    get_users();

    function get_users() {
        $.ajax({
            url: '../Controllers/user_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'get_all_users'
            },
            success: function (data) {
                if (!data['users']) {
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
                    oTable.fnClearTable();
                    for (i = 0; i < data['users'].length; i++) {
                        oTable.fnAddData([
                            data['users'][i]['id'],
                            data['users'][i]['name'],
                            data['users'][i]['groups'],
                            '<button class="edit_user" id="' + data['users'][i]['id'] + '">Edit User</button>' +
                                    '<button class="delete_user" id="' + data['users'][i]['id'] + '">Delete User</button>'
                        ]);
                    }
                }
            }
        });
    }

    function delete_user(user_id, row) {
        $.ajax({
            url: '../Controllers/user_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'delete_user',
                userid: user_id
            },
            success: function (data) {
                $('#dialog').dialog({
                    title: 'Notice',
                    buttons: [{
                            text: 'OK',
                            click: function () {
                                $(this).dialog('close');
                            }
                        }]
                }).html(data['message']);

                if (data['success']) {
                    //delete row from the table
                    oTable.fnDeleteRow(oTable.fnGetPosition(row));
                }
            }
        });
    }
});

