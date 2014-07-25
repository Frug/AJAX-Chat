<?php
/*
 * @package AJAX_Chat
 * @author Mirko Girgenti (Bomdia)
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */
?>
<html>
    <head>
        <title>Login in Your MySql server</title>
        <link rel="stylesheet" type="text/css" href="install.css">
        <script src="install.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    </head>
    
    <body>
        <div id="main">
            <div id="title">
            MySql Login
            </div>
            <div id="login">
                <form id="flogin" name="login">
                 <table>
                    <tr>
                        <td>Username </td>
                        <td><input type="text" name="username"></td>
                        <td><div id="uer" class="err" style="display: none;"></div></td>
                    </tr>
                    <tr>
                        <td>Password </td>
                        <td><input type="password" name="passwd"></td>
                    </tr>
                    <tr>
                        <td>Hostname </td>
                        <td><input type="text" value="localhost" name="host"></td>
                        <td><div id="her" class="err" style="display: none;"></div></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="button" value="Login" onclick="Login();Anim('status')"></td>
                    </tr>
                </table>
                </form>
                <div id="status" class="red"></div>
            </div>
        </div>
    </body>
</html>