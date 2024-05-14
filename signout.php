
<?php 
require_once 'User.php';
require_once 'necessary/operation.php';

?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>sign out</title>
         <link rel="icon" href="icon2.png">
        <link rel="stylesheet" href="styles/bootstrap.min.css">  
        
    </head>
    <body>
        <div class="container">
            <div class='alert alert-primary d-flex gap-2'><p class='m-0'>signing out</p></div></div>
        <?php
        if(isLoggedIn()){
             setcookie('user_id', $_COOKIE['user_id'], time() - (86400 * 30), "/");
             go('index.php');
        }
        ?>
    </body>
</html>
