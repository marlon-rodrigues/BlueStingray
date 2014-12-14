<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    date_default_timezone_set('UTC');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Group Editor | Blue Stingray</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Load all the libraries -->
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" href="../css/main.css">

        <script src="../Libraries/jquery-1.11.1.js"></script>
        <script src="../Libraries/DataTables-1.9.4/media/js/jquery.js"></script>
        <script src="../Libraries/DataTables-1.9.4/media/js/jquery.dataTables.js"></script>
        <link rel="stylesheet" href="../Libraries/jquery-ui-1.11.2.custom/jquery-ui.min.css" />
        <script src="../Libraries/jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="../Libraries/DataTables-1.9.4/media/css/demo_table.css" />
        <link rel="stylesheet" href="../Libraries/DataTables-1.9.4/media/css/demo_table_jui.css" />
        
        <script src="../js/helpers/loader.js"></script>
        <script src="../js/groups.js"></script>
    </head>
    <body>
        <div id="container">
            <header>
                <div id="header_nav"></div>
            </header>
            
            <div id="content">
                
                <div id="accordion_groups" align="center">
                    <h3>Groups List</h3>
                    <div>
                        <div id="table">
                            <table id="group_table" class="display">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th width="150px" align="left">Name</th>
                                        <th width="70px"></th>
                                    </tr>
                                </thead>
                            </table>
                            <div style="clear:both"></div>
                        </div>

                        <div align="center" style="margin-top:2em; font-size: 1em">
                            <button id="add_btn">Add New Group</button>
                        </div>
                        
                    </div>
                </div>
                
                <form name="addedit_form" id="addedit_form" action="../Views/User_Manager_AddEdit.php" method="POST">
                    <input type="hidden" name="groupid" id="groupid" value=-1>
                    <input type="hidden" name="groupname" id="groupname" value=''>
                </form>
                
                <div id="dialog"></div>
            </div>
            
            <footer>
                <div id="footer_nav"></div>
            </footer>
        </div>
    </body>
</html>
