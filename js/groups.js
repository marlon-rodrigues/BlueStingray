$(document).ready(function() {
        //setup accordion
    $('#accordion_groups').accordion({
        icons: false,
        heightStyle: "content"
    });
    
        //setup datatable
    var oTable = $('#group_table').dataTable({
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
            "sEmptyTable": "There are no groups in the system"
        },
        "bDeferRender": true,
        "bAutoWidth": false,
        "bPaginate": true,
        "aoColumnDefs": [
            {"asSorting": ["asc"], "aTargets": [1]},
            {"bSortable": false, "aTargets": [2]},
            {"bVisible": false, "aTargets": [0]}
        ],
        "fnDrawCallback": function (oSettings) {
            $('.edit_group').button({
                icons: {
                    primary: "ui-icon-pencil"
                },
                text: false
            }).unbind('click').bind('click', function () {
                $('#groupid').val($(this).attr('id'));
                $('#groupname').val($(this).parent().prev().html());
                $('#addedit_form').submit();
            });

            $('.delete_group').button({
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
                                delete_group(id, row);
                            }
                        },
                        {
                            text: 'NO',
                            click: function () {
                                $(this).dialog('close');
                            }
                        }]
                }).html('Are you sure you want to delete user: ' + $(this).parent().prev().html() + ' ?');
            });
        }
    });

    //setup buttons
    $('#add_btn').button({
        icons: {
            primary: "ui-icon-circle-plus"
        }
    }).click(function () {
        $('#groupid').val('');
        $('#groupname').val('');
        $('#addedit_form').submit();
    });

    get_groups();

    function get_groups() {
        $.ajax({
            url: '../Controllers/group_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'get_all_groups'
            },
            success: function (data) {
                if (!data['groups']) {
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
                    for (i = 0; i < data['groups'].length; i++) {
                        oTable.fnAddData([
                            data['groups'][i]['id'],
                            data['groups'][i]['group_name'],
                            '<button class="edit_group" id="' + data['groups'][i]['id'] + '">Edit Group</button>' +
                                    '<button class="delete_group" id="' + data['groups'][i]['id'] + '">Delete Group</button>'
                        ]);
                    }
                }
            }
        });
    }

    function delete_group(group_id, row) {
        $.ajax({
            url: '../Controllers/group_manager.php',
            type: 'POST',
            dataType: 'json',
            data: {
                function_name: 'delete_group',
                groupid: group_id
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

