<!DOCTYPE html>
<?php
session_start();
require_once 'necessary/dbcon.php';

function getnumofcomments($answerid) {
    $conn = dbcon();
    if (!$conn) {
        return 0; // Return 0 if connection fails
    }

    // Prepare SQL query to count the total number of questions for the user
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM comment_answer WHERE answerid=?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return 0; // Return 0 if query preparation fails
    }

    $stmt->bind_param("i", $answerid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch total number of questions
    $row = $result->fetch_assoc();
    $total_comments = $row['total'];

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $total_comments;
}

function getanswer($answerid) {
    $conn = dbcon();
    if (!$conn) {
        return array(); // Return an empty array if connection fails
    }

    // Prepare SQL query to fetch user's questions with pagination
    $stmt = $conn->prepare("SELECT answer.*,stack_user.name FROM answer,stack_user
             WHERE stack_user.id=answer.userid AND answer.id= ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return array(); // Return an empty array if query preparation fails
    }

    $stmt->bind_param("i", $answerid);
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

function getanswercomments($answerid, $offset, $LIMIT) {
    $conn = dbcon();
    if (!$conn) {
        return array(); // Return an empty array if connection fails
    }

    // Prepare SQL query to fetch user's questions with pagination
    $stmt = $conn->prepare("SELECT comment_answer.*,stack_user.name  FROM comment_answer,stack_user
             where stack_user.id=comment_answer.userid AND answerid= ? ORDER BY time LIMIT ?,?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return array(); // Return an empty array if query preparation fails
    }

    $stmt->bind_param("iii", $answerid, $offset, $LIMIT);
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

$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$limit = 10; // Number of questions per page
$offset = ($page - 1) * $limit; // Offset for SQL query
// Retrieve user's questions
$comments = getanswercomments($_GET['id'], $offset, $limit);
$totalcomments = getnumofcomments($_GET['id'])
?>
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
        ?>
        <?php
        foreach (getanswer($_GET['id']) as $answer) {
            echo '<div class="container"><div class = "card text-center">
            <div class = "card-header">
            Answer
            </div>
            <div class = "card-body">
            <h5 class = "card-title">' . $answer['title'] . '</h5>
            <p class = "card-text">' . $answer['content'] . '</p>
            
            </div>
            <div class = "card-footer text-body-light"><span>
            ' . $answer['time'] . '  </span><br><span style="backgound-color:gray;">  Posted by : ' . $answer['name'] . '</span>
            </div>
            </div>
            </div><hr>'
            ;
            //close the div
        }
        foreach ($comments as $comment) {
            echo '<div class="container"><div class = "card text-center">
            <div class = "card-header">
            comment
            </div>
            <div class = "card-body">
            
            <p class = "card-text">' . $comment['content'] . '</p>
            
            </div>
            <div class = "card-footer text-body-light"><span>
            ' . $comment['time'] . '  </span><br><span style="backgound-color:gray;">  Posted by : ' . $comment['name'] . '</span>
            </div>
            </div>
            </div><hr>';
        }
        ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <!-- Previous page button -->
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>&id=<?php echo $_GET['id']; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <!-- Page numbers -->
                <?php for ($i = 1; $i <= ceil($totalcomments / $limit); $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo ($page + 1); ?>&id=<?php echo $_GET['id']; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
                <!-- Next page button -->
                <li class="page-item <?php echo ($page >= ceil($totalcomments / $limit)) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>&id=<?php echo $_GET['id']; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>

    </body>
</html>
