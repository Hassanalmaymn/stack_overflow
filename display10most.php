<?php
session_start();
$active = 'home';
require_once 'necessary/dbcon.php';

// Function to get the total number of questions for a user
function getTotalQuestions() {
    $conn = dbcon();
    if (!$conn) {
        return 0; // Return 0 if connection fails
    }

    // Prepare SQL query to count the total number of questions for the user
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM question");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return 0; // Return 0 if query preparation fails
    }


    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch total number of questions
    $row = $result->fetch_assoc();
    $total_questions = $row['total'];

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $total_questions;
}

function getQuestions($offset, $limit) {
    $conn = dbcon();
    if (!$conn) {
        return array(); // Return an empty array if connection fails
    }

    // Prepare SQL query to fetch user's questions with pagination
    $stmt = $conn->prepare("SELECT stack_user.name,question.userid,question.id AS qid,question.title,question.content,question.time,COUNT(answer.id)
       AS numberofanswers FROM ((question LEFT JOIN answer ON question.id=answer.questionid) JOIN stack_user ON stack_user.id=question.userid) 
            GROUP BY question.id ORDER BY COUNT(answer.id) 
              DESC LIMIT ?, ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return array(); // Return an empty array if query preparation fails
    }

    $stmt->bind_param("ii", $offset, $limit);
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

$total_questions = getTotalQuestions();
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$limit = 10; // Number of questions per page
$offset = ($page - 1) * $limit; // Offset for SQL query
// Retrieve user's questions
$questions = getQuestions($offset, $limit);

// Retrieve total number of user's questions for pagination
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

            /* foreach (get10questionwithmostanswers() as $question) {


              echo '<div class="card bg-light-emphasis"><a class="card-title" style="text-decoration:none; '
              . '" href="question.php?id='.$question['qid'].'"><h4 style="background-color:gray;">' . $question['title'] . '</h4></a>'
              . '<p class="card-body">' . $question['content'] . '</p><div class="card-footer">' . $question['time'].'<span '
              . 'class="card-footer justify-text-end" style="align-text:end;">number of answers: '.
              $question['numberofanswers'].'</span><span class="card-footer justify-item-end bg-warning">Posted by : '.$question['name'].'</span></div></div><hr>';
              }
              ?> */
            ?>
            <?php foreach ($questions as $question): ?>
                <div class="question-box">
                    <h4><a style='text-decoration: none;' href='question.php?id=<?php echo $question['qid']; ?>'><?php echo $question['title']; ?></a></h4>
                    <p><?php echo $question['content']; ?></p>
                    <p>Created Time: <?php echo $question['time']; ?></p> <!-- Display time for the question -->
                    <p>Posted by: <?php echo $question['name']; ?></p>
                    <p>number of answers : <?php echo $question['numberofanswers']; ?><p>
                        <!-- Edit and delete buttons -->

                </div>
            <?php endforeach; ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <!-- Previous page button -->
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <!-- Page numbers -->
                    <?php for ($i = 1; $i <= ceil($total_questions / $limit); $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <!-- Next page button -->
                    <li class="page-item <?php echo ($page >= ceil($total_questions / $limit)) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>


        </div>

        <script  src="scripts/bootstrap.bundle.min.js"></script>
    </body>
</html>
