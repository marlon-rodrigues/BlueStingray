<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    date_default_timezone_set('UTC');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>User Editor | Blue Stingray</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Load all the libraries -->
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" href="../css/main.css">

        <script src="../Libraries/jquery-1.11.1.js"></script>
        <link rel="stylesheet" href="../Libraries/jquery-ui-1.11.2.custom/jquery-ui.min.css" />
        <script src="../Libraries/jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
        
        <script src="../Libraries/jquery-multiselect/jquery-multiselect-min.js"></script>
        <link rel="stylesheet" href="../Libraries/jquery-multiselect/jquery-multiselect.css" />
        
        <script src="../js/Helpers/loader.js"></script>
        <script src="../js/add_edit_user.js"></script>
    </head>
    <body>
        <div id="container">
            <header>
                <div id="header_nav"></div>
            </header>
            
            <div id="content">
                <label id="userid" style="display:none"><?php echo $_POST['userid'] ?></label>
                
                <div id="accordion_addedit_users" align="center">
                    <h3><?php echo ($_POST['userid'] == '') ? 'Add New User' : 'User Name: ' . $_POST['username'] ?></h3>
                    <div>
                        <table width="100%" class="table_info">
                            <tr>
                                <td>User Name:</td>
                                <td>
                                    <input type="text" name="username" id="username" style="width: 300px">
                                </td>
                            </tr>
                            <tr>
                                <td width="150px">Groups:</td>
                                <td>
                                    <select id="groups_select" name="groups_select" multiple="multiple"></select>
                                </td>
                            </tr>
                            
                        </table>
                        
                        <div align="center" class="update_buttons">
                            <button id="cancel_btn">Cancel</button>
                            <button id="add_btn"><?php echo ($_POST['userid'] == '') ? 'Add User' : 'Update User' ?></button>
                        </div>
                    </div>
                </div>

                <div id="dialog"></div>
            </div>
            
            <footer>
                <div id="footer_nav"></div>
            </footer>
        </div>
    </body>
</html>

