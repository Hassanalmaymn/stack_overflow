<?php
session_start();
$active = 'home';
require_once 'necessary/dbcon.php';
require_once 'necessary/operation.php'; // Include operation.php to resolve the error

function deleteanswer($answer_id) {
    $conn = dbcon();
    if (!$conn) {
        return false; // Return false if connection fails
    }

    // Prepare SQL query to delete the question
    $stmt = $conn->prepare("DELETE FROM answer WHERE id = ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return false; // Return false if query preparation fails
    }

    $stmt->bind_param("i", $answer_id);
    $result = $stmt->execute();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $result;
}

// Handle delete request if the delete button is clicked
if (isset($_POST['delete_answer'])) {
    $answer_id = $_POST['answer_id'];
    if (deleteanswer($answer_id)) {
        // If deletion is successful, redirect back to the same page
        header("Location: index.php");
        exit();
    } else {
        // Handle deletion failure
        echo "Failed to delete the question.";
    }
}
function deletecomment($comment_id) {
    $conn = dbcon();
    if (!$conn) {
        return false; // Return false if connection fails
    }

    // Prepare SQL query to delete the question
    $stmt = $conn->prepare("DELETE FROM comment_answer WHERE id = ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return false; // Return false if query preparation fails
    }

    $stmt->bind_param("i", $comment_id);
    $result = $stmt->execute();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $result;
}

// Handle delete request if the delete button is clicked
if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    if (deletecomment($comment_id)) {
        // If deletion is successful, redirect back to the same page
        header("Location: index.php");
        exit();
    } else {
        // Handle deletion failure
        echo "Failed to delete the question.";
    }
}
function getQuestioncomments($questionid) {
    $conn = dbcon();
    if (!$conn) {
        return array(); // Return an empty array if connection fails
    }

    // Prepare SQL query to fetch user's questions with pagination
    $stmt = $conn->prepare("SELECT comment_answer.*,stack_user.name  FROM comment_answer,stack_user
             where stack_user.id=comment_answer.userid AND questionid= ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return array(); // Return an empty array if query preparation fails
    }

    $stmt->bind_param("i", $questionid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch questions and return them as an array
    $questions = array();
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $questions;
}





// Function to retrieve a specific question by its ID
function getQuestionById($questionId) {
    $conn = dbcon();
    if (!$conn) {
        return null; // Return null if connection fails
    }

    // Prepare SQL query to fetch the question by ID
    $stmt = $conn->prepare("SELECT * FROM question WHERE id = ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return null; // Return null if query preparation fails
    }

    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the question and return it as an associative array
    $question = $result->fetch_assoc();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $question;
}

function deleteQuestion($question_id) {
    $conn = dbcon();
    if (!$conn) {
        return false; // Return false if connection fails
    }

    // Prepare SQL query to delete the question
    $stmt = $conn->prepare("DELETE FROM question WHERE id = ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return false; // Return false if query preparation fails
    }

    $stmt->bind_param("i", $question_id);
    $result = $stmt->execute();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $result;
}

// Handle delete request if the delete button is clicked
if (isset($_POST['delete_question'])) {
    $question_id = $_POST['question_id'];
    if (deleteQuestion($question_id)) {
        // If deletion is successful, redirect back to the same page
        header("Location: index.php");
        exit();
    } else {
        // Handle deletion failure
        echo "Failed to delete the question.";
    }
}

// Check if the question ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $questionId = $_GET['id'];
    $question = getQuestionById($questionId);

    if ($question) {
        // Display the question
        // Add your HTML code here to display the question details
        // Example: echo "<h2>{$question['title']}</h2><p>{$question['content']}</p>";
        // No longer displaying the "Answer this question" link
    } else {
        echo "Question not found.";
    }
} else {
    echo "Question ID is not provided.";
}

$comments = getQuestioncomments($_GET['id']);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>stack overflow</title>
        <link rel="icon" href="icon2.png">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="styles/bootstrap.min.css">   
        <style>
            body{
                 background-image: linear-gradient(38deg, rgba(98, 98, 98,0.01) 0%, rgba(98, 98, 98,0.01) 10%,rgba(235, 235, 235,0.01) 10%, rgba(235, 235, 235,0.01) 25%,rgba(253, 253, 253,0.01) 25%, rgba(253, 253, 253,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(148deg, rgba(177, 177, 177,0.03) 0%, rgba(177, 177, 177,0.03) 10%,rgba(7, 7, 7,0.03) 10%, rgba(7, 7, 7,0.03) 25%,rgba(24, 24, 24,0.03) 25%, rgba(24, 24, 24,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(303deg, rgba(28, 28, 28,0.03) 0%, rgba(28, 28, 28,0.03) 10%,rgba(180, 180, 180,0.03) 10%, rgba(180, 180, 180,0.03) 25%,rgba(63, 63, 63,0.03) 25%, rgba(63, 63, 63,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(196deg, rgba(231, 231, 231,0.02) 0%, rgba(231, 231, 231,0.02) 10%,rgba(175, 175, 175,0.02) 10%, rgba(175, 175, 175,0.02) 25%,rgba(252, 252, 252,0.02) 25%, rgba(252, 252, 252,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(20deg, rgba(96, 96, 96,0.03) 0%, rgba(96, 96, 96,0.03) 10%,rgba(95, 95, 95,0.03) 10%, rgba(95, 95, 95,0.03) 25%,rgba(33, 33, 33,0.03) 25%, rgba(33, 33, 33,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(339deg, rgba(241, 241, 241,0.02) 0%, rgba(241, 241, 241,0.02) 10%,rgba(164, 164, 164,0.02) 10%, rgba(164, 164, 164,0.02) 25%,rgba(68, 68, 68,0.02) 25%, rgba(68, 68, 68,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(317deg, rgba(218, 218, 218,0.02) 0%, rgba(218, 218, 218,0.02) 10%,rgba(179, 179, 179,0.02) 10%, rgba(179, 179, 179,0.02) 25%,rgba(24, 24, 24,0.02) 25%, rgba(24, 24, 24,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(58deg, rgba(118, 118, 118,0.01) 0%, rgba(118, 118, 118,0.01) 10%,rgba(139, 139, 139,0.01) 10%, rgba(139, 139, 139,0.01) 25%,rgba(114, 114, 114,0.01) 25%, rgba(114, 114, 114,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(135deg, rgba(5, 5, 5,0.03) 0%, rgba(5, 5, 5,0.03) 10%,rgba(90, 90, 90,0.03) 10%, rgba(90, 90, 90,0.03) 25%,rgba(75, 75, 75,0.03) 25%, rgba(75, 75, 75,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(54deg, rgba(78, 78, 78,0.03) 0%, rgba(78, 78, 78,0.03) 10%,rgba(102, 102, 102,0.03) 10%, rgba(102, 102, 102,0.03) 25%,rgba(126, 126, 126,0.03) 25%, rgba(126, 126, 126,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(144deg, rgba(34, 34, 34,0.03) 0%, rgba(34, 34, 34,0.03) 10%,rgba(34, 34, 34,0.03) 10%, rgba(34, 34, 34,0.03) 25%,rgba(186, 186, 186,0.03) 25%, rgba(186, 186, 186,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(277deg, rgba(63, 63, 63,0.02) 0%, rgba(63, 63, 63,0.02) 10%,rgba(111, 111, 111,0.02) 10%, rgba(111, 111, 111,0.02) 25%,rgba(221, 221, 221,0.02) 25%, rgba(221, 221, 221,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(288deg, rgba(22, 22, 22,0.03) 0%, rgba(22, 22, 22,0.03) 10%,rgba(222, 222, 222,0.03) 10%, rgba(222, 222, 222,0.03) 25%,rgba(103, 103, 103,0.03) 25%, rgba(103, 103, 103,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(321deg, rgba(138, 138, 138,0.01) 0%, rgba(138, 138, 138,0.01) 10%,rgba(89, 89, 89,0.01) 10%, rgba(89, 89, 89,0.01) 25%,rgba(1, 1, 1,0.01) 25%, rgba(1, 1, 1,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(173deg, rgba(21, 21, 21,0.03) 0%, rgba(21, 21, 21,0.03) 10%,rgba(162, 162, 162,0.03) 10%, rgba(162, 162, 162,0.03) 25%,rgba(36, 36, 36,0.03) 25%, rgba(36, 36, 36,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(152deg, rgba(53, 53, 53,0.01) 0%, rgba(53, 53, 53,0.01) 10%,rgba(106, 106, 106,0.01) 10%, rgba(106, 106, 106,0.01) 25%,rgba(77, 77, 77,0.01) 25%, rgba(77, 77, 77,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(299deg, rgba(0, 0, 0,0.03) 0%, rgba(0, 0, 0,0.03) 10%,rgba(0, 0, 0,0.03) 10%, rgba(0, 0, 0,0.03) 25%,rgba(30, 30, 30,0.03) 25%, rgba(30, 30, 30,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(92deg, rgba(237, 237, 237,0.03) 0%, rgba(237, 237, 237,0.03) 10%,rgba(66, 66, 66,0.03) 10%, rgba(66, 66, 66,0.03) 25%,rgba(10, 10, 10,0.03) 25%, rgba(10, 10, 10,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(46deg, rgba(231, 231, 231,0.03) 0%, rgba(231, 231, 231,0.03) 10%,rgba(33, 33, 33,0.03) 10%, rgba(33, 33, 33,0.03) 25%,rgba(37, 37, 37,0.03) 25%, rgba(37, 37, 37,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(176deg, rgba(125, 125, 125,0.01) 0%, rgba(125, 125, 125,0.01) 10%,rgba(210, 210, 210,0.01) 10%, rgba(210, 210, 210,0.01) 25%,rgba(112, 112, 112,0.01) 25%, rgba(112, 112, 112,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(100deg, rgba(70, 70, 70,0.01) 0%, rgba(70, 70, 70,0.01) 10%,rgba(46, 46, 46,0.01) 10%, rgba(46, 46, 46,0.01) 25%,rgba(203, 203, 203,0.01) 25%, rgba(203, 203, 203,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(304deg, rgba(100, 100, 100,0.01) 0%, rgba(100, 100, 100,0.01) 10%,rgba(50, 50, 50,0.01) 10%, rgba(50, 50, 50,0.01) 25%,rgba(196, 196, 196,0.01) 25%, rgba(196, 196, 196,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(186deg, rgba(40, 40, 40,0.02) 0%, rgba(40, 40, 40,0.02) 10%,rgba(224, 224, 224,0.02) 10%, rgba(224, 224, 224,0.02) 25%,rgba(62, 62, 62,0.02) 25%, rgba(62, 62, 62,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(6deg, rgba(37, 37, 37,0.03) 0%, rgba(37, 37, 37,0.03) 10%,rgba(219, 219, 219,0.03) 10%, rgba(219, 219, 219,0.03) 25%,rgba(43, 43, 43,0.03) 25%, rgba(43, 43, 43,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(42deg, rgba(212, 212, 212,0.01) 0%, rgba(212, 212, 212,0.01) 10%,rgba(24, 24, 24,0.01) 10%, rgba(24, 24, 24,0.01) 25%,rgba(15, 15, 15,0.01) 25%, rgba(15, 15, 15,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(23deg, rgba(122, 122, 122,0.03) 0%, rgba(122, 122, 122,0.03) 10%,rgba(149, 149, 149,0.03) 10%, rgba(149, 149, 149,0.03) 25%,rgba(44, 44, 44,0.03) 25%, rgba(44, 44, 44,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(152deg, rgba(196, 196, 196,0.03) 0%, rgba(196, 196, 196,0.03) 10%,rgba(151, 151, 151,0.03) 10%, rgba(151, 151, 151,0.03) 25%,rgba(70, 70, 70,0.03) 25%, rgba(70, 70, 70,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(157deg, rgba(43, 43, 43,0.03) 0%, rgba(43, 43, 43,0.03) 10%,rgba(20, 20, 20,0.03) 10%, rgba(20, 20, 20,0.03) 25%,rgba(161, 161, 161,0.03) 25%, rgba(161, 161, 161,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(1deg, rgba(89, 89, 89,0.03) 0%, rgba(89, 89, 89,0.03) 10%,rgba(154, 154, 154,0.03) 10%, rgba(154, 154, 154,0.03) 25%,rgba(197, 197, 197,0.03) 25%, rgba(197, 197, 197,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(58deg, rgba(117, 117, 117,0.01) 0%, rgba(117, 117, 117,0.01) 10%,rgba(134, 134, 134,0.01) 10%, rgba(134, 134, 134,0.01) 25%,rgba(217, 217, 217,0.01) 25%, rgba(217, 217, 217,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(47deg, rgba(55, 55, 55,0.03) 0%, rgba(55, 55, 55,0.03) 10%,rgba(97, 97, 97,0.03) 10%, rgba(97, 97, 97,0.03) 25%,rgba(4, 4, 4,0.03) 25%, rgba(4, 4, 4,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(90deg, rgb(255,255,255),rgb(255,255,255));
            }
            .question-box {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
                background-color:white;
            }
            .btn-group button {
                margin-right: 5px; /* Adjust margin between buttons */
            }
            *{
                margin: 0;
                padding: 0;
            }
            .rate {
                display: flex; /* Use flexbox */
                justify-content: center; /* Center horizontally */
                align-items: center; /* Center vertically */
                height: 46px;
                padding: 0 10px;
            }
            .rate:not(:checked) > input {
                position:absolute;
                top:-9999px;
            }
            .rate:not(:checked) > label {
                float:right;
                width:1em;
                overflow:hidden;
                white-space:nowrap;
                cursor:pointer;
                font-size:30px;
                color:#ccc;
            }
            .rate:not(:checked) > label:before {
                content: '★ ';
            }
            .rate > input:checked ~ label {
                color: #ffc700;
            }
            .rate:not(:checked) > label:hover,
            .rate:not(:checked) > label:hover ~ label {
                color: #deb217;
            }
            .rate > input:checked + label:hover,
            .rate > input:checked + label:hover ~ label,
            .rate > input:checked ~ label:hover,
            .rate > input:checked ~ label:hover ~ label,
            .rate > label:hover ~ input:checked ~ label {
                color: #c59b08;
            }

        </style>
    </head>
    <body>
        <?php
        require_once 'necessary/stack_navbar.php';
        require_once 'necessary/operation.php';
        ?>
        <hr>
        <?php
        foreach (getthequestion($_GET['id']) as $question) {
            echo '<div class="container"><div class = "card text-center">
            <div class = "card-header">
            Question
            </div>
            <div class = "card-body">
            <h5 class = "card-title">' . $question['title'] . '</h5>
            <p class = "card-text">' . $question['content'] . '</p>
            
            </div>
            <div class = "card-footer text-body-light"><span>
            ' . $question['time'] . '  </span><span style="backgound-color:rgb(240, 240, 240);">  Posted by : ' . $question['name'] . '</span><br>';
            if (isset($_COOKIE['user_id']) && $question['userid'] === $_COOKIE['user_id']) {
                echo '<div class="btn-group">
                            <form method="post" action="edit_question.php?question_id=' . $question['id'] . '">
                    <input type="hidden" name="question_id" value="' . $question['id'] . '">
                                <button type="submit" name="edit_question" class="btn btn-warning">Edit</button> 
                            </form>
                            <form method="post" action="' . $_SERVER['PHP_SELF'] . '?id=' . $question['id'] . '" onsubmit="return confirmDelete();">
                                <input type="hidden" name="question_id" value="' . $question['id'] . '">
                                <button type="submit" name="delete_question" class="btn btn-danger">Delete</button>
                            </form>
            </div>';
            }
            if (isLoggedIn()) {
                // "Answer this question" button with orange color using inline CSS
                echo '<form method="get" action="create_answer.php">
              <input type="hidden" name="id" value="' . $question['id'] . '">
              <button type="submit" class="btn btn-primary" style="background-color: orange; border-color: orange; margin-top:3px;">Answer this question</button>
            </form>';
            echo '<a class="btn btn-success m-2" href="create_comment_forQuestion.php?id=' . $question['id'] . '">add comment to a question</a>';
            }

            echo '</div></div></div><hr>';
        }
        foreach (getthequestionanswers($_GET['id']) as $answer) {
            $averageRate = getAverageRate($answer['id']);
            echo '<div class="container"><div class="card text-center">
                    <div class="card-header">
                    Answer
                    </div>
                    <div class="card-body">
                    <a style="text-decoration:none;" href="answer.php?id=' . $answer['id'] . '"><h5 class="card-title">' . $answer['title'] . '</h5></a>
                    <p class="card-text">' . $answer['content'] . '</p>
                    
                    </div>
                    <div class="card-footer text-body-light"><span>
                    ' . $answer['answertime'] . '  </span><span style="background-color:rgb(240, 240, 240);">  Posted by : ' . $answer['name'] . '</span>';
            echo '<div class="average-rate">Average Rate: ' . $averageRate . '</div>';

            if (isset($_COOKIE['user_id']) && $answer['userid'] === $_COOKIE['user_id']) {
                echo '<div class="btn-group">
                            <form method="post" action="edit_answer.php">
                    <input type="hidden" name="answer_id" value="' . $answer['id'] . '">
                        <input type="hidden" name="answer_title" value="' . $answer['title'] . '">
                            <input type="hidden" name="answer_content" value="' . $answer['content'] . '">
                                <button type="submit" name="edit_answer" class="btn btn-warning">Edit</button> 
                            </form>
                            <form method="post" action="' . $_SERVER['PHP_SELF'] . '?id=' . $answer['id'] . '" onsubmit="return confirmDelete();">
                                <input type="hidden" name="answer_id" value="' . $answer['id'] . '">
                                <button type="submit" name="delete_answer" class="btn btn-danger">Delete</button>
                            </form>
            </div>';
            }





            if (isset($_COOKIE['user_id'])) {
                echo '
                    </div>
                    <div class="rate" id="rate-' . $answer['id'] . '">
                       <input type="radio" id="star5-' . $answer['id'] . '" name="rate-' . $answer['id'] . '" value="5" onclick="submitRating(' . $answer['id'] . ', ' . $_GET['id'] . ', ' . $_COOKIE['user_id'] . ')" />
                       <label for="star5-' . $answer['id'] . '" title="text">5 stars</label>
                       <input type="radio" id="star4-' . $answer['id'] . '" name="rate-' . $answer['id'] . '" value="4" onclick="submitRating(' . $answer['id'] . ', ' . $_GET['id'] . ', ' . $_COOKIE['user_id'] . ')" />
                       <label for="star4-' . $answer['id'] . '" title="text">4 stars</label>
                       <input type="radio" id="star3-' . $answer['id'] . '" name="rate-' . $answer['id'] . '" value="3" onclick="submitRating(' . $answer['id'] . ', ' . $_GET['id'] . ', ' . $_COOKIE['user_id'] . ')" />
                       <label for="star3-' . $answer['id'] . '" title="text">3 stars</label>
                       <input type="radio" id="star2-' . $answer['id'] . '" name="rate-' . $answer['id'] . '" value="2" onclick="submitRating(' . $answer['id'] . ', ' . $_GET['id'] . ', ' . $_COOKIE['user_id'] . ')" />
                       <label for="star2-' . $answer['id'] . '" title="text">2 stars</label>
                       <input type="radio" id="star1-' . $answer['id'] . '" name="rate-' . $answer['id'] . '" value="1" onclick="submitRating(' . $answer['id'] . ', ' . $_GET['id'] . ', ' . $_COOKIE['user_id'] . ')" />
                       <label for="star1-' . $answer['id'] . '" title="text">1 star</label>
                       </div>';
            }

            echo'</div>
                    
                    </div><hr>';
        }

        foreach ($comments as $comment) {
            echo '<div class="container"><div class = "card text-center">
            <div class = "card-header">
            comment for question
            </div>
            <div class = "card-body">
            
            <p class = "card-text">' . $comment['content'] . '</p>
            
            </div>
            <div class = "card-footer text-body-light"><span>
            ' . $comment['time'] . '  </span><br><span style="backgound-color:gray;">  Posted by : ' . $comment['name'] . '</span><br>
            
            
            ';
            if (isset($_COOKIE['user_id']) && $comment['userid'] === $_COOKIE['user_id']) {
                echo '<div class="btn-group">
                            <form method="post" action="edit_comment.php?id=' . $comment['id'] . '">
                                <input type="hidden" name="answer_id" value="' . $_GET['id'] . '">
                    <input type="hidden" name="comment_id" value="' . $comment['id'] . '">
                            <input type="hidden" name="comment_content" value="' . $comment['content'] . '">
                                <button type="submit" name="edit_comment" class="btn btn-warning">Edit</button> 
                            </form>
                            <form method="post" action="' . $_SERVER['PHP_SELF'] . '?id=' . $comment['id'] . '" onsubmit="return confirmDelete();">
                                <input type="hidden" name="comment_id" value="' . $comment['id'] . '">
                                <button type="submit" name="delete_comment" class="btn btn-danger">Delete</button>
                            </form>
            </div><br>';
            }
            echo '</div></div></div><hr>';
        }





        ?>


        <script>

            // Function to handle rating submission
            function submitRating(answerId, questionId, userId) {
                // Get the selected rating value
                var rating = document.querySelector('input[name="rate-' + answerId + '"]:checked').value;

                // Update the rating in localStorage
                localStorage.setItem('rating_' + answerId, rating);

                // AJAX request to send rating data to Rate.php
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'Rate.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Display the response from Rate.php
                        console.log(xhr.responseText);
                    }
                };
                xhr.send('answer_id=' + encodeURIComponent(answerId) + '&questionid=' + encodeURIComponent(questionId) + '&user_id=' + encodeURIComponent(userId) + '&rating=' + encodeURIComponent(rating));
            }

            function initializeRatings() {
    // Check if user ID is present in cookies
    var userId = getCookie('user_id');
    if (userId !== "") {
        // Loop through each answer
        document.querySelectorAll('.rate').forEach(function (rateElement) {
            var answerId = rateElement.id.split('-')[1]; // Extract answer ID from rate ID

            // AJAX request to fetch user's rating for the current answer
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'FetchRating.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var rating = xhr.responseText;
                    if (rating !== "") {
                        // Set the corresponding radio button as checked based on the retrieved rating
                        rateElement.querySelector('input[value="' + rating + '"]').checked = true;
                    }
                }
            };
            xhr.send('answer_id=' + encodeURIComponent(answerId) + '&user_id=' + encodeURIComponent(userId));
        });
    }
}

// Function to set a cookie
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

// Function to get a cookie
function getCookie(name) {
    var nameEQ = name + "=";
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1, cookie.length);
        }
        if (cookie.indexOf(nameEQ) === 0) {
            return cookie.substring(nameEQ.length, cookie.length);
        }
    }
    return null;
}

// Call initializeRatings() when the document is ready to set ratings from the server and cookies
document.addEventListener('DOMContentLoaded', function () {
    initializeRatings();
});

// Call initializeRatings() when the page loads to set ratings from localStorage
            window.onload = initializeRatings;

            function confirmDelete() {
                return confirm("Are you sure you want to delete this question?");
            }
        </script>
    </body>
</html>