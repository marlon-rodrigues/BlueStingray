<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Users Manager Project | Blue Stingray</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Load all the libraries -->
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/footer.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="Libraries/jquery-1.11.1.js"></script>
        <link rel="stylesheet" href="../Libraries/jquery-ui-1.11.2.custom/jquery-ui.min.css" />
        <script src="../Libraries/jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
        
        
        <script src="js/helpers/loader.js"></script>
        <script src="js/sys_manager.js"></script>
        <!--<link rel="shortcut icon" href="images/lbn_globe.png">-->
    </head>
    <body>
        <div id="container">
            <header>
                <div id="header_nav"></div>
            </header>
            
            <div id="content">
                <div id="accordion_sys_manager" align="center">
                    <h3>System Manager</h3>
                    <div>
                        <div class="option_managers" align="center">
                            <button id="users_btn">Manage Users</button>
                            <label>Manage Users</label>
                        </div>

                        <div class="option_managers" align="center" style="margin-top: 1em; margin-left: 0.8em">
                            <button id="groups_btn">Manage Groups</button>
                            <label>Manage Groups</label>
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
