<?php
session_start();
$active = 'home';
$word = $_GET['search'];

require_once 'necessary/dbcon.php';

function getTotalQuestions($word) {
    $conn = dbcon();
    if (!$conn) {
        return 0; // Return 0 if connection fails
    }

    // Prepare SQL query to count the total number of questions for the user
    $stmt = "SELECT COUNT(*) as total FROM question WHERE question.id IN 
 (SELECT question.id FROM question WHERE question.title LIKE '%" . $word . "%' OR  question.content LIKE '%" . $word . "%');";

    $result = mysqli_query($conn, $stmt);

    $row = mysqli_fetch_array($result);

    return $row['total'];
}

function findn($word, $offset, $limit) {
    $db = dbcon();

    $sql = "SELECT question.id AS qid,question.title,question.content,stack_user.name,question.time, COUNT(answer.id)
 AS numberofanswers FROM ((stack_user join question ON stack_user.id=question.userid) LEFT JOIN answer ON answer.questionid=question.id) 
 WHERE question.id IN 
 (SELECT question.id FROM question WHERE question.title LIKE '%" . $word . "%' OR  question.content LIKE '%" . $word . "%') GROUP BY
  question.id ORDER by numberofanswers DESC limit " . $offset . "," . $limit . " ;";

    $result = mysqli_query($db, $sql);
    $assocq = array();
    while ($row = mysqli_fetch_array($result)) {

        $assocq[] = $row;
    }
    return $assocq;
}

$total_questions = getTotalQuestions($word);
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$limit = 10; // Number of questions per page
$offset = ($page - 1) * $limit; // Offset for SQL query
// Retrieve user's questions
$questions = findn($word, $offset, $limit);
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
                foreach ($questions as $question) {


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


                            echo '<div class="question-box">'
                            . '<h4><a style="text-decoration:none;" href="question.php?id=' . $question['qid'] . '">' . $question['title'] . '</a></h4> '
                            . '<p>' . $question['content'] . '</p>'
                            . '<p>number of answers: ' . $question['numberofanswers'] . '</p><br>    '
                            . ' <p>Created Time:' . $question['time'] . '</p><br>' .
                            '<p>Posted by:  ' . $question['name'] . '</p>  '
                            . '</div><hr>';
                        }
                    } if ($_POST['choice'] == 2) {
                        echo '<hr><h3 style="text-align-center">Your Answers</h3>';

                        foreach (findmyanswer($_GET['search'], $_COOKIE['user_id']) as $answer) {/*


                          echo '<div class="card bg-light-emphasis"><a class="card-title" style="text-decoration:none; '
                          . '" href="answer.php?id=' . $answer['id'] . '"><h4 style="background-color:gray;">' . $answer['title'] . '</h4></a>'
                          . '<p class="card-body">' . $answer['content'] . '</p><div class="card-footer">' . $answer['time'] . ''
                          . '<span class="card-footer justify-item-end bg-warning">Posted by : ' . $answer['name'] . '</span></div></div><hr>'; */

                            echo '<div class="container"><div class="card text-center">
                    <div class="card-header">
                    Answer
                    </div>
                    <div class="card-body">
                    <a style="text-decoration:none;" href="answer.php?id=' . $answer['id'] . '"><h5 class="card-title">' . $answer['title'] . '</h5></a>
                    <p class="card-text">' . $answer['content'] . '</p>
                    
                    </div>
                    <div class="card-footer text-body-light"><span>
                    ' . $answer['time'] . '  </span><span style="background-color:rgb(240, 240, 240);">  Posted by : ' . $answer['name'] . '</span>';
                            /* if (isset($_COOKIE['user_id'])) {
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
                              } */

                            echo'</div>
                    
                    </div><hr>';
                        }
                    }
                }
            }
            ?>

        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <!-- Previous page button -->
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $_GET['search']; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <!-- Page numbers -->
                <?php for ($i = 1; $i <= ceil($total_questions / $limit); $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $_GET['search']; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
                <!-- Next page button -->
                <li class="page-item <?php echo ($page >= ceil($total_questions / $limit)) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $_GET['search']; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>

        <script  src="scripts/bootstrap.bundle.min.js"></script>
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
