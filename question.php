<?php session_start();?>

<!DOCTYPE html>

<html>
  <head>
        <meta charset="UTF-8">
        <title>stack overflow</title>
        <link rel="icon" href="icon.png">
        <link rel="stylesheet" href="styles/bootstrap.min.css">    
    </head>
    <body>
        <?php require_once 'necessary/stack_navbar.php';
                require_once 'necessary/operation.php';
        foreach (getthequestion($_GET['id']) as $question){
            echo '<div style="left:50ps"><h3 class="">'.$question['title'].'</h3><p>'.$question['content'].'</p></div>';
        }
        ?>
    </body>
</html>
