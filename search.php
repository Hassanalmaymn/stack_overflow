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
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="styles/bootstrap.min.css">   
        <style>
            .question-box {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
            }
            .btn-group button {
                margin-right: 5px; /* Adjust margin between buttons */
            }
        </style>
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


                    /* echo '<div class="card bg-light-emphasis"><a class="card-title" style="text-decoration:none; '
                      . '" href="question.php?id=' . $question['qid'] . '"><h4 style="background-color:gray;">' . $question['title'] . '</h4></a>'
                      . '<p class="card-body">' . $question['content'] . '</p><div class="card-footer">' . $question['time'] . '<span '
                      . 'class="card-footer justify-text-end" style="align-text:end;">number of answers: ' .
                      $question['numberofanswers'] . '</span><span class="card-footer justify-item-end bg-warning">Posted by : ' . $question['name'] . '</span></div></div><hr>'; */

                    echo '<div class="question-box">'
                    . '<h4><a style="text-decoration:none;" href="question.php?id=' . $question['qid'] . '">' . $question['title'] . '</a></h4> '
                    . '<p>' . $question['content'] . '</p>'
                    . '<p>number of answers: ' . $question['numberofanswers'] . '</p><br>    '
                    . ' <p>Created Time:' . $question['time'] . '</p><br>' .
                    '<p>Posted by:  ' . $question['name'] . '</p>    '
                    . '</div>';
                }
            } else {
                echo '<form method="POST" action="search.php?search=' . $_GET['search'] . '">
                <div class="form-check">
                        <input class="form-check-input" type="radio" value="1" name="choice" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                         display questions
                        </label>
                        </div>
                        <div class="form-check">
                <input class="form-check-input" type="radio" value="2" name="choice" id="flexRadioDefault2" checked>
                    <label class="form-check-label" for="flexRadioDefault2">
                     display answers
                </label>
                </div>
                <input type="submit" name="submit" value="submit" class="btn btn-outline-primary">
            </form>';
                if (isset($_POST['choice'])) {
                    if ($_POST['choice'] == 1) {
                        echo '<h3 style="text-align-center">Your Questions</h3>';

                        foreach (searchuser($_GET['search'], $_COOKIE['user_id']) as $question) {


                            echo '<div class="card bg-light-emphasis"><a class="card-title" style="text-decoration:none; '
                            . '" href="question.php?id=' . $question['qid'] . '"><h4 style="background-color:gray;">' . $question['title'] . '</h4></a>'
                            . '<p class="card-body">' . $question['content'] . '</p><div class="card-footer">' . $question['time'] . '<span '
                            . 'class="card-footer justify-text-end" style="align-text:end;">number of answers: ' .
                            $question['numberofanswers'] . '</span><span class="card-footer justify-item-end bg-warning">Posted by : ' . $question['name'] . '</span></div></div><hr>';
                        }
                    } if ($_POST['choice'] == 2) {
                        echo '<hr><h3 style="text-align-center">Your Answers</h3>';

                        foreach (findmyanswer($_GET['search'], $_COOKIE['user_id']) as $answer) {


                            echo '<div class="card bg-light-emphasis"><a class="card-title" style="text-decoration:none; '
                            . '" href="answer.php?id=' . $answer['id'] . '"><h4 style="background-color:gray;">' . $answer['title'] . '</h4></a>'
                            . '<p class="card-body">' . $answer['content'] . '</p><div class="card-footer">' . $answer['time'] . ''
                            . '<span class="card-footer justify-item-end bg-warning">Posted by : ' . $answer['name'] . '</span></div></div><hr>';
                        }
                    }
                }
            }
            ?>

        </div>

        <script  src="scripts/bootstrap.bundle.min.js"></script>
    </body>
</html>
