<?php
session_start();
$active = 'home';
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>stack overflow</title>
        <link rel="icon" href="icon.png">
        <link rel="stylesheet" href="styles/bootstrap.min.css">    
    </head>
    <body>
        <?php
        require_once 'necessary/stack_navbar.php';
        require_once 'necessary/operation.php';
        foreach (getthequestion($_GET['id']) as $question) {
            echo '<div class="container"><div class = "card text-center">
            <div class = "card-header">
            Question
            </div>
            <div class = "card-body">
            <h5 class = "card-title">'.$question['title'].'</h5>
            <p class = "card-text">'.$question['content'].'</p>
            
            </div>
            <div class = "card-footer text-body-light"><span>
            '.$question['time'].'  </span><span style="backgound-color:gray;">  Posted by : '.$question['name'].'</span>
            </div>
            </div>';
            //close the div
        }
         foreach (getthequestionanswers($_GET['id']) as $answer){
             echo '<div class = "card text-center">
            <div class = "card-header">
            Answer
            </div>
            <div class = "card-body">
            <h5 class = "card-title">'.$answer['title'].'</h5>
            <p class = "card-text">'.$answer['content'].'</p>
            
            </div>
            <div class = "card-footer text-body-light"><span>
            '.$answer['answertime'].'  </span><span style="backgound-color:gray;">  Posted by : '.$answer['name'].'</span>
            </div>
            </div></div>';
         }
        
        ?>
    </body>
</html>
