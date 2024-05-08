<?php
session_start();
$active = 'home';
$word = $_GET['search']
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
        <b class="card-footer "></b>
        <?php require_once 'necessary/stack_navbar.php'; ?>
        <hr>
        <div class="container">
            <?php
            require_once 'User.php';
            require_once 'necessary/operation.php';
            if (!isLoggedIn()) {
                foreach (find($_GET['search']) as $question) {


                    echo '<div class="card bg-light-emphasis"><a class="card-title" style="text-decoration:none; '
                    . '" href="question.php?id=' . $question['qid'] . '"><h4 style="background-color:gray;">' . $question['title'] . '</h4></a>'
                    . '<p class="card-body">' . $question['content'] . '</p><div class="card-footer">' . $question['time'] . '<span '
                    . 'class="card-footer justify-text-end" style="align-text:end;">number of answers: ' .
                    $question['numberofanswers'] . '</span><span class="card-footer justify-item-end bg-warning">Posted by : ' . $question['name'] . '</span></div></div><hr>';
                }
            } else {
                    echo '<h3 style="text-align-center">Your Questions</h3>';

                foreach (searchuser($word, $_COOKIE['user_id']) as $question) {


                    echo '<div class="card bg-light-emphasis"><a class="card-title" style="text-decoration:none; '
                    . '" href="question.php?id=' . $question['qid'] . '"><h4 style="background-color:gray;">' . $question['title'] . '</h4></a>'
                    . '<p class="card-body">' . $question['content'] . '</p><div class="card-footer">' . $question['time'] . '<span '
                    . 'class="card-footer justify-text-end" style="align-text:end;">number of answers: ' .
                    $question['numberofanswers'] . '</span><span class="card-footer justify-item-end bg-warning">Posted by : ' . $question['name'] . '</span></div></div><hr>';
                }
                echo '<hr><h3 style="text-align-center">Your Answers</h3>';

                foreach (findmyanswer($word, $_COOKIE['user_id']) as $answer) {


                    echo '<div class="card bg-light-emphasis"><a class="card-title" style="text-decoration:none; '
                    . '" href="answer.php?id=' . $answer['id'] . '"><h4 style="background-color:gray;">' . $answer['title'] . '</h4></a>'
                    . '<p class="card-body">' . $answer['content'] . '</p><div class="card-footer">' . $answer['time'] . ''
                    . '<span class="card-footer justify-item-end bg-warning">Posted by : ' . $answer['name'] . '</span></div></div><hr>';
                }
            }
            ?>

        </div>

        <script  src="scripts/bootstrap.bundle.min.js"></script>
    </body>
</html>
