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
            body{
                 background-image: linear-gradient(38deg, rgba(98, 98, 98,0.01) 0%, rgba(98, 98, 98,0.01) 10%,rgba(235, 235, 235,0.01) 10%, rgba(235, 235, 235,0.01) 25%,rgba(253, 253, 253,0.01) 25%, rgba(253, 253, 253,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(148deg, rgba(177, 177, 177,0.03) 0%, rgba(177, 177, 177,0.03) 10%,rgba(7, 7, 7,0.03) 10%, rgba(7, 7, 7,0.03) 25%,rgba(24, 24, 24,0.03) 25%, rgba(24, 24, 24,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(303deg, rgba(28, 28, 28,0.03) 0%, rgba(28, 28, 28,0.03) 10%,rgba(180, 180, 180,0.03) 10%, rgba(180, 180, 180,0.03) 25%,rgba(63, 63, 63,0.03) 25%, rgba(63, 63, 63,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(196deg, rgba(231, 231, 231,0.02) 0%, rgba(231, 231, 231,0.02) 10%,rgba(175, 175, 175,0.02) 10%, rgba(175, 175, 175,0.02) 25%,rgba(252, 252, 252,0.02) 25%, rgba(252, 252, 252,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(20deg, rgba(96, 96, 96,0.03) 0%, rgba(96, 96, 96,0.03) 10%,rgba(95, 95, 95,0.03) 10%, rgba(95, 95, 95,0.03) 25%,rgba(33, 33, 33,0.03) 25%, rgba(33, 33, 33,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(339deg, rgba(241, 241, 241,0.02) 0%, rgba(241, 241, 241,0.02) 10%,rgba(164, 164, 164,0.02) 10%, rgba(164, 164, 164,0.02) 25%,rgba(68, 68, 68,0.02) 25%, rgba(68, 68, 68,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(317deg, rgba(218, 218, 218,0.02) 0%, rgba(218, 218, 218,0.02) 10%,rgba(179, 179, 179,0.02) 10%, rgba(179, 179, 179,0.02) 25%,rgba(24, 24, 24,0.02) 25%, rgba(24, 24, 24,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(58deg, rgba(118, 118, 118,0.01) 0%, rgba(118, 118, 118,0.01) 10%,rgba(139, 139, 139,0.01) 10%, rgba(139, 139, 139,0.01) 25%,rgba(114, 114, 114,0.01) 25%, rgba(114, 114, 114,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(135deg, rgba(5, 5, 5,0.03) 0%, rgba(5, 5, 5,0.03) 10%,rgba(90, 90, 90,0.03) 10%, rgba(90, 90, 90,0.03) 25%,rgba(75, 75, 75,0.03) 25%, rgba(75, 75, 75,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(54deg, rgba(78, 78, 78,0.03) 0%, rgba(78, 78, 78,0.03) 10%,rgba(102, 102, 102,0.03) 10%, rgba(102, 102, 102,0.03) 25%,rgba(126, 126, 126,0.03) 25%, rgba(126, 126, 126,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(144deg, rgba(34, 34, 34,0.03) 0%, rgba(34, 34, 34,0.03) 10%,rgba(34, 34, 34,0.03) 10%, rgba(34, 34, 34,0.03) 25%,rgba(186, 186, 186,0.03) 25%, rgba(186, 186, 186,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(277deg, rgba(63, 63, 63,0.02) 0%, rgba(63, 63, 63,0.02) 10%,rgba(111, 111, 111,0.02) 10%, rgba(111, 111, 111,0.02) 25%,rgba(221, 221, 221,0.02) 25%, rgba(221, 221, 221,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(288deg, rgba(22, 22, 22,0.03) 0%, rgba(22, 22, 22,0.03) 10%,rgba(222, 222, 222,0.03) 10%, rgba(222, 222, 222,0.03) 25%,rgba(103, 103, 103,0.03) 25%, rgba(103, 103, 103,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(321deg, rgba(138, 138, 138,0.01) 0%, rgba(138, 138, 138,0.01) 10%,rgba(89, 89, 89,0.01) 10%, rgba(89, 89, 89,0.01) 25%,rgba(1, 1, 1,0.01) 25%, rgba(1, 1, 1,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(173deg, rgba(21, 21, 21,0.03) 0%, rgba(21, 21, 21,0.03) 10%,rgba(162, 162, 162,0.03) 10%, rgba(162, 162, 162,0.03) 25%,rgba(36, 36, 36,0.03) 25%, rgba(36, 36, 36,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(152deg, rgba(53, 53, 53,0.01) 0%, rgba(53, 53, 53,0.01) 10%,rgba(106, 106, 106,0.01) 10%, rgba(106, 106, 106,0.01) 25%,rgba(77, 77, 77,0.01) 25%, rgba(77, 77, 77,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(299deg, rgba(0, 0, 0,0.03) 0%, rgba(0, 0, 0,0.03) 10%,rgba(0, 0, 0,0.03) 10%, rgba(0, 0, 0,0.03) 25%,rgba(30, 30, 30,0.03) 25%, rgba(30, 30, 30,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(92deg, rgba(237, 237, 237,0.03) 0%, rgba(237, 237, 237,0.03) 10%,rgba(66, 66, 66,0.03) 10%, rgba(66, 66, 66,0.03) 25%,rgba(10, 10, 10,0.03) 25%, rgba(10, 10, 10,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(46deg, rgba(231, 231, 231,0.03) 0%, rgba(231, 231, 231,0.03) 10%,rgba(33, 33, 33,0.03) 10%, rgba(33, 33, 33,0.03) 25%,rgba(37, 37, 37,0.03) 25%, rgba(37, 37, 37,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(176deg, rgba(125, 125, 125,0.01) 0%, rgba(125, 125, 125,0.01) 10%,rgba(210, 210, 210,0.01) 10%, rgba(210, 210, 210,0.01) 25%,rgba(112, 112, 112,0.01) 25%, rgba(112, 112, 112,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(100deg, rgba(70, 70, 70,0.01) 0%, rgba(70, 70, 70,0.01) 10%,rgba(46, 46, 46,0.01) 10%, rgba(46, 46, 46,0.01) 25%,rgba(203, 203, 203,0.01) 25%, rgba(203, 203, 203,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(304deg, rgba(100, 100, 100,0.01) 0%, rgba(100, 100, 100,0.01) 10%,rgba(50, 50, 50,0.01) 10%, rgba(50, 50, 50,0.01) 25%,rgba(196, 196, 196,0.01) 25%, rgba(196, 196, 196,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(186deg, rgba(40, 40, 40,0.02) 0%, rgba(40, 40, 40,0.02) 10%,rgba(224, 224, 224,0.02) 10%, rgba(224, 224, 224,0.02) 25%,rgba(62, 62, 62,0.02) 25%, rgba(62, 62, 62,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(6deg, rgba(37, 37, 37,0.03) 0%, rgba(37, 37, 37,0.03) 10%,rgba(219, 219, 219,0.03) 10%, rgba(219, 219, 219,0.03) 25%,rgba(43, 43, 43,0.03) 25%, rgba(43, 43, 43,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(42deg, rgba(212, 212, 212,0.01) 0%, rgba(212, 212, 212,0.01) 10%,rgba(24, 24, 24,0.01) 10%, rgba(24, 24, 24,0.01) 25%,rgba(15, 15, 15,0.01) 25%, rgba(15, 15, 15,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(23deg, rgba(122, 122, 122,0.03) 0%, rgba(122, 122, 122,0.03) 10%,rgba(149, 149, 149,0.03) 10%, rgba(149, 149, 149,0.03) 25%,rgba(44, 44, 44,0.03) 25%, rgba(44, 44, 44,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(152deg, rgba(196, 196, 196,0.03) 0%, rgba(196, 196, 196,0.03) 10%,rgba(151, 151, 151,0.03) 10%, rgba(151, 151, 151,0.03) 25%,rgba(70, 70, 70,0.03) 25%, rgba(70, 70, 70,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(157deg, rgba(43, 43, 43,0.03) 0%, rgba(43, 43, 43,0.03) 10%,rgba(20, 20, 20,0.03) 10%, rgba(20, 20, 20,0.03) 25%,rgba(161, 161, 161,0.03) 25%, rgba(161, 161, 161,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(1deg, rgba(89, 89, 89,0.03) 0%, rgba(89, 89, 89,0.03) 10%,rgba(154, 154, 154,0.03) 10%, rgba(154, 154, 154,0.03) 25%,rgba(197, 197, 197,0.03) 25%, rgba(197, 197, 197,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(58deg, rgba(117, 117, 117,0.01) 0%, rgba(117, 117, 117,0.01) 10%,rgba(134, 134, 134,0.01) 10%, rgba(134, 134, 134,0.01) 25%,rgba(217, 217, 217,0.01) 25%, rgba(217, 217, 217,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(47deg, rgba(55, 55, 55,0.03) 0%, rgba(55, 55, 55,0.03) 10%,rgba(97, 97, 97,0.03) 10%, rgba(97, 97, 97,0.03) 25%,rgba(4, 4, 4,0.03) 25%, rgba(4, 4, 4,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(90deg, rgb(255,255,255),rgb(255,255,255));
            }
            .question-box {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
                background-color: white;
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
