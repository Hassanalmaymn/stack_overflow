<?php
session_start();
$active = 'home';
$limit = 10;
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
    $stmt = $conn->prepare("SELECT question.*,stack_user.name FROM question,stack_user where stack_user.id=question.userid ORDER BY time DESC LIMIT ?, ?");
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

        <?php require_once 'necessary/stack_navbar.php'; ?>
        <hr>
        <div class="container">
            <?php
            require_once 'User.php';
            require_once 'necessary/operation.php';
            if (isLoggedIn()) {
                echo '<a class="btn btn-outline-success" href="create_question.php">add Question</a><hr>';
            }

            /* foreach (get10recentquestion() as $question) {


              echo '<div class="card bg-light-emphasis"><a class="card-title" style="text-decoration:none; '
              . '" href="question.php?id=' . $question['qid'] . '"><h4 style="background-color:gray;">' . $question['title'] . '</h4></a>'
              . '<p class="card-body">' . $question['content'] . '</p><div class="card-footer">' . $question['time'] .
              '<span class="card-footer justify-item-end bg-warning">Posted by : ' . $question['name'] . '</span></div></div><hr>';
              }
              ?> */
            ?>
            <?php foreach ($questions as $question): ?>
                <div class="question-box">
                    <h4><a style='text-decoration: none;' href='question.php?id=<?php echo $question['id']; ?>'><?php echo $question['title']; ?></a></h4>
                    <p><?php echo $question['content']; ?></p>
                    <p>Created Time: <?php echo $question['time']; ?></p> <!-- Display time for the question -->
                    <p>Posted by: <?php echo $question['name']; ?></p>
                    <!-- Edit and delete buttons -->

                </div>
            <?php endforeach; //fix pagination bug    ?>
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
