<?php
session_start();
$active = 'home';
require_once 'necessary/dbcon.php';
require_once 'necessary/operation.php'; // Include operation.php to resolve the error

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
            }

            echo '</div></div></div><hr>';
        }
        foreach (getthequestionanswers($_GET['id']) as $answer) {
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
            if (isset($_COOKIE['user_id'])) {
                echo '</div>
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

// Function to initialize ratings from localStorage
            function initializeRatings() {
                // Loop through each answer
                document.querySelectorAll('.rate').forEach(function (rateElement) {
                    var answerId = rateElement.id.split('-')[1]; // Extract answer ID from rate ID
                    var storedRating = localStorage.getItem('rating_' + answerId); // Get stored rating

                    // If a rating is found in localStorage, set the corresponding radio button as checked
                    if (storedRating !== null) {
                        rateElement.querySelector('input[value="' + storedRating + '"]').checked = true;
                    }
                });
            }

// Call initializeRatings() when the document is ready to set ratings from localStorage
            document.addEventListener('DOMContentLoaded', initializeRatings);

// Call initializeRatings() when the page loads to set ratings from localStorage
            window.onload = initializeRatings;

            function confirmDelete() {
                return confirm("Are you sure you want to delete this question?");
            }
        </script>
    </body>
</html>