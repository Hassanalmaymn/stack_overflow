<!DOCTYPE html>
<?php
session_start();
$active = "answer page";
require_once 'necessary/dbcon.php';
require_once 'User.php';

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
            ' . $answer['time'] . '  </span><br><span style="backgound-color:gray;">  Posted by : ' . $answer['name'] . '</span><br>
            ';

            //close the div
        } if (isset($_COOKIE['user_id']) && $answer['userid'] === $_COOKIE['user_id']) {
            echo '<div class="btn-group">
                            <form method="post" action="edit_answer.php?' . $answer['id'] . '">
                    <input type="hidden" name="answer_id" value="' . $answer['id'] . '">
                        <input type="hidden" name="answer_title" value="' . $answer['title'] . '">
                            <input type="hidden" name="answer_content" value="' . $answer['content'] . '">
                                <button type="submit" name="edit_answer" class="btn btn-warning">Edit</button> 
                            </form>
                            <form method="post" action="' . $_SERVER['PHP_SELF'] . '?id=' . $answer['id'] . '" onsubmit="return confirmDelete();">
                                <input type="hidden" name="answer_id" value="' . $answer['id'] . '">
                                <button type="submit" name="delete_answer" class="btn btn-danger">Delete</button>
                            </form>
            </div><br>';
        }
        if (isLoggedIn()) {
            echo '<a class="btn btn-success m-2" href="create_comment.php?id=' . $answer['id'] . '">add comment to an answer</a>';
        }
        echo '</div></div></div><hr>';
        foreach ($comments as $comment) {
            echo '<div class="container"><div class = "card text-center">
            <div class = "card-header">
            comment for answer
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
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this answer?");
            }
        </script>
    </body>
</html>
