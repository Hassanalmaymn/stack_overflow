<?php
// Include necessary files
session_start();
require_once 'necessary/dbcon.php';
require_once 'User.php'; // Assuming you have the necessary functions in this file
$active = 'useranswers';

// Redirect to sign-in page if user is not logged in
if (!isLoggedIn()) {
    header("Location: Signin.php");
    exit(); // Stop further execution
}

// Function to retrieve user's questions from the database
function getUseranswers($user_id, $offset, $limit) {
    $conn = dbcon();
    if (!$conn) {
        return array(); // Return an empty array if connection fails
    }

    // Prepare SQL query to fetch user's questions with pagination
    $stmt = $conn->prepare("SELECT * FROM answer WHERE userid = ? ORDER BY time DESC LIMIT ?, ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return array(); // Return an empty array if query preparation fails
    }

    $stmt->bind_param("iii", $user_id, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch questions and return them as an array
    $answers = array();
    while ($row = $result->fetch_assoc()) {
        $answers[] = $row;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $answers;
}

// Function to get the total number of questions for a user
function getTotalUseranswers($user_id) {
    $conn = dbcon();
    if (!$conn) {
        return 0; // Return 0 if connection fails
    }

    // Prepare SQL query to count the total number of questions for the user
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM answer WHERE userid = ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return 0; // Return 0 if query preparation fails
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch total number of questions
    $row = $result->fetch_assoc();
    $total_answers = $row['total'];

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $total_answers;
}

// Function to delete a question by its ID
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
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Handle deletion failure
        echo "Failed to delete the answer.";
    }
}

// Assuming user_id is retrieved from the session or cookie
$user_id = $_COOKIE['user_id']; // Update this line with actual user ID retrieval
// Pagination parameters
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$limit = 10; // Number of questions per page
$offset = ($page - 1) * $limit; // Offset for SQL query
// Retrieve user's questions
$user_answers = getUseranswers($user_id, $offset, $limit);

// Retrieve total number of user's questions for pagination
$total_answers = getTotalUseranswers($user_id);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My answerss</title>
        <!-- Bootstrap CSS -->
        <link rel="icon" href="icon2.png">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="styles/bootstrap.min.css">
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

        <div class="container">
            <h2>My Answers</h2>

            <!-- Display user's questions or message if no questions -->
            <?php if (empty($user_answers)): ?>
                <p>You don't have any answers</p>
            <?php else: ?>
                <?php foreach ($user_answers as $answer): ?>
                    <div class="question-box">
                        <a style="text-decoration:none;" href="answer.php?id=<?php echo $answer['id']; ?>"><h4><?php echo $answer['title']; ?></h4></a>
                        <p><?php echo $answer['content']; ?></p>
                        <p>Created Time: <?php echo $answer['time']; ?></p> <!-- Display time for the answer -->
                        <!-- Edit and delete buttons -->
                        <div class="btn-group">
                            <form method="post" action="edit_answer.php">
                                <input type="hidden" name="answer_id" value="<?php echo $answer['id']; ?>">
                                <button type="submit" name="edit_answer" class="btn btn-warning">Edit</button> <!-- Changed class to "btn-warning" -->
                            </form>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return confirmDelete();">
                                <input type="hidden" name="answer_id" value="<?php echo $answer['id']; ?>">
                                <button type="submit" name="delete_answer" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Pagination -->
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
                    <?php for ($i = 1; $i <= ceil($total_answers / $limit); $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <!-- Next page button -->
                    <li class="page-item <?php echo ($page >= ceil($total_answers / $limit)) ? 'disabled' : ''; ?>">
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
                                    return confirm("Are you sure you want to delete this answer?");
                                }
        </script>

    </body>
</html>
