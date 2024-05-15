<?php
session_start();
$active = 'home';
$limit = 10;
require_once 'necessary/dbcon.php';

// Function to delete a question by its ID
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
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Handle deletion failure
        echo "Failed to delete the question.";
    }
}

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
    $stmt = $conn->prepare("SELECT question.*,stack_user.name,stack_user.id AS userid FROM question,stack_user where stack_user.id=question.userid ORDER BY time DESC LIMIT ?, ?");
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
        <link rel="stylesheet" href="styles/bgcolor.css">   
        <style>
            .question-box {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
            }
            .btn-group button {
                margin-right: 5px; /* Adjust margin between buttons */
            }
            .button {
                position: relative;
                width: 150px;
                height: 40px;
                cursor: pointer;
                display: flex;
                align-items: center;
                border: 1px solid #34974d;
                background-color: #3aa856;

            }

            .button, .button__icon, .button__text {
                transition: all 0.3s;
                text-decoration: none; /* Remove underline */
                color: inherit; /* Inherit text color */
            }

            .button .button__text {
                transform: translateX(30px);
                color: #fff;
                font-weight: 600;
                padding-left: -60px; /* Adjusted padding */
            }

            .button .button__icon {
                position: absolute;
                transform: translateX(135px);
                height: 40px;
                width: 39px;
                background-color: #34974d;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .button .svg {
                width: 30px;
                stroke: #fff;

            }

            .button:hover {
                background: #34974d;
                text-decoration: none; /* Remove underline */
                color: inherit; /* Inherit text color */
            }

            .button:hover .button__text {
                color: transparent;
            }

            .button:hover .button__icon {
                width: 148px;
                transform: translateX(0);
            }

            .button:active .button__icon {
                background-color: #2e8644;
            }

            .button:active {
                border: 1px solid #2e8644;
            }
            .button a {
                text-decoration: none; /* Remove underline */
                color: inherit; /* Inherit text color */
            }
        </style>

    </head>
    <body class="bg-stack">

        <?php require_once 'necessary/stack_navbar.php'; ?>
        <hr>
        <div class="container">
            <?php
            require_once 'User.php';
            require_once 'necessary/operation.php';
            if (isLoggedIn()) {
                echo '<a href="create_question.php" class="button">
                          <span class="button__text"> Add Question </span>
                          <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg"><line y2="19" y1="5" x2="12" x1="12"></line><line y2="12" y1="12" x2="19" x1="5"></line></svg></span>
                      </a><hr>';
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
                    <?php
                    if (isLoggedIn() && $_COOKIE['user_id'] === $question['userid']) {
                        echo '<div class="btn-group">
                            <form method="post" action="edit_question.php?question_id=' . $question['id'] . '">
                    <input type="hidden" name="question_id" value="' . $question['id'] . '">
                                <button type="submit" name="edit_question" class="btn btn-warning">Edit</button> 
                            </form>
                            <form method="post" action="' . $_SERVER['PHP_SELF'] . '" onsubmit="return confirmDelete();">
                                <input type="hidden" name="question_id" value="' . $question['id'] . '">
                                <button type="submit" name="delete_question" class="btn btn-danger">Delete</button>
                            </form>
                        </div>';
                    }
                    ?>

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
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this question?");
            }
        </script>
    </body>
</html>
