<?php
session_start();
$active = 'home';
require_once 'necessary/dbcon.php';

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
            ' . $question['time'] . '  </span><span style="backgound-color:gray;">  Posted by : ' . $question['name'] . '</span><br>';
            if ($question['userid'] === $_COOKIE['user_id']) {
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
            echo '</div>
            </div>
            </div><hr>';
        }
        foreach (getthequestionanswers($_GET['id']) as $answer) {
            echo '<div class="container"><div class = "card text-center">
            <div class = "card-header">
            Answer
            </div>
            <div class = "card-body">
            <a style="text-decoration:none;" href="answer.php?id=' . $answer['id'] . '"><h5 class = "card-title">' . $answer['title'] . '</h5></a>
            <p class = "card-text">' . $answer['content'] . '</p>
            
            </div>
            <div class = "card-footer text-body-light"><span>
            ' . $answer['answertime'] . '  </span><span style="backgound-color:gray;">  Posted by : ' . $answer['name'] . '</span>
            </div>
            </div>
            </div><hr>';
        }
        ?>
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this question?");
            }
        </script>
    </body>
</html>