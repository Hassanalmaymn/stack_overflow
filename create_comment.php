<?php
session_start();
$active = "create_comment";
require_once 'necessary/dbcon.php';

// Function to get a question by its ID
function getAnswerById($answerId) {
    $conn = dbcon();
    if (!$conn) {
        return null; // Return null if connection fails
    }

    // Prepare SQL query to fetch question by ID
    $stmt = $conn->prepare("SELECT * FROM answer WHERE id = ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return null; // Return null if query preparation fails
    }

    $stmt->bind_param("i", $answerId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch question details
    $answer = $result->fetch_assoc();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $answer;
}

// Function to insert an answer into the database
function insertComment($answerId, $userId, $content) {
    $conn = dbcon();
    if (!$conn) {
        return false; // Return false if connection fails
    }

    // Prepare SQL statement to insert the answer
    $stmt = $conn->prepare("INSERT INTO comment_answer (userid, answerid, content) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return false; // Return false if query preparation fails
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("sss", $userId, $answerId, $content);
    $result = $stmt->execute();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $result; // Return true if insertion is successful, false otherwise
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $answerId = $_GET['id'];
    $userId = $_COOKIE['user_id'];
    $content = $_POST['content'];

    // Insert the answer into the database
    $success = insertComment($answerId, $userId, $content);

    if ($success) {
        // Redirect back to the question page or display a success message
        header("Location: answer.php?id=$answerId");
        exit;
    } else {
        // Handle the case where answer insertion fails
        echo "Failed to submit the comment.";
    }
}

// Retrieve the question details based on the question ID
if (isset($_GET['id'])) {
    $answerId = $_GET['id'];
    // Retrieve the question details based on the question ID
    $answer = getAnswerById($answerId);

    if ($answer) {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <title>comment</title>
                <link rel="icon" href="icon2.png">
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                         background-image: linear-gradient(38deg, rgba(98, 98, 98,0.01) 0%, rgba(98, 98, 98,0.01) 10%,rgba(235, 235, 235,0.01) 10%, rgba(235, 235, 235,0.01) 25%,rgba(253, 253, 253,0.01) 25%, rgba(253, 253, 253,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(148deg, rgba(177, 177, 177,0.03) 0%, rgba(177, 177, 177,0.03) 10%,rgba(7, 7, 7,0.03) 10%, rgba(7, 7, 7,0.03) 25%,rgba(24, 24, 24,0.03) 25%, rgba(24, 24, 24,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(303deg, rgba(28, 28, 28,0.03) 0%, rgba(28, 28, 28,0.03) 10%,rgba(180, 180, 180,0.03) 10%, rgba(180, 180, 180,0.03) 25%,rgba(63, 63, 63,0.03) 25%, rgba(63, 63, 63,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(196deg, rgba(231, 231, 231,0.02) 0%, rgba(231, 231, 231,0.02) 10%,rgba(175, 175, 175,0.02) 10%, rgba(175, 175, 175,0.02) 25%,rgba(252, 252, 252,0.02) 25%, rgba(252, 252, 252,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(20deg, rgba(96, 96, 96,0.03) 0%, rgba(96, 96, 96,0.03) 10%,rgba(95, 95, 95,0.03) 10%, rgba(95, 95, 95,0.03) 25%,rgba(33, 33, 33,0.03) 25%, rgba(33, 33, 33,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(339deg, rgba(241, 241, 241,0.02) 0%, rgba(241, 241, 241,0.02) 10%,rgba(164, 164, 164,0.02) 10%, rgba(164, 164, 164,0.02) 25%,rgba(68, 68, 68,0.02) 25%, rgba(68, 68, 68,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(317deg, rgba(218, 218, 218,0.02) 0%, rgba(218, 218, 218,0.02) 10%,rgba(179, 179, 179,0.02) 10%, rgba(179, 179, 179,0.02) 25%,rgba(24, 24, 24,0.02) 25%, rgba(24, 24, 24,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(58deg, rgba(118, 118, 118,0.01) 0%, rgba(118, 118, 118,0.01) 10%,rgba(139, 139, 139,0.01) 10%, rgba(139, 139, 139,0.01) 25%,rgba(114, 114, 114,0.01) 25%, rgba(114, 114, 114,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(135deg, rgba(5, 5, 5,0.03) 0%, rgba(5, 5, 5,0.03) 10%,rgba(90, 90, 90,0.03) 10%, rgba(90, 90, 90,0.03) 25%,rgba(75, 75, 75,0.03) 25%, rgba(75, 75, 75,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(54deg, rgba(78, 78, 78,0.03) 0%, rgba(78, 78, 78,0.03) 10%,rgba(102, 102, 102,0.03) 10%, rgba(102, 102, 102,0.03) 25%,rgba(126, 126, 126,0.03) 25%, rgba(126, 126, 126,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(144deg, rgba(34, 34, 34,0.03) 0%, rgba(34, 34, 34,0.03) 10%,rgba(34, 34, 34,0.03) 10%, rgba(34, 34, 34,0.03) 25%,rgba(186, 186, 186,0.03) 25%, rgba(186, 186, 186,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(277deg, rgba(63, 63, 63,0.02) 0%, rgba(63, 63, 63,0.02) 10%,rgba(111, 111, 111,0.02) 10%, rgba(111, 111, 111,0.02) 25%,rgba(221, 221, 221,0.02) 25%, rgba(221, 221, 221,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(288deg, rgba(22, 22, 22,0.03) 0%, rgba(22, 22, 22,0.03) 10%,rgba(222, 222, 222,0.03) 10%, rgba(222, 222, 222,0.03) 25%,rgba(103, 103, 103,0.03) 25%, rgba(103, 103, 103,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(321deg, rgba(138, 138, 138,0.01) 0%, rgba(138, 138, 138,0.01) 10%,rgba(89, 89, 89,0.01) 10%, rgba(89, 89, 89,0.01) 25%,rgba(1, 1, 1,0.01) 25%, rgba(1, 1, 1,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(173deg, rgba(21, 21, 21,0.03) 0%, rgba(21, 21, 21,0.03) 10%,rgba(162, 162, 162,0.03) 10%, rgba(162, 162, 162,0.03) 25%,rgba(36, 36, 36,0.03) 25%, rgba(36, 36, 36,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(152deg, rgba(53, 53, 53,0.01) 0%, rgba(53, 53, 53,0.01) 10%,rgba(106, 106, 106,0.01) 10%, rgba(106, 106, 106,0.01) 25%,rgba(77, 77, 77,0.01) 25%, rgba(77, 77, 77,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(299deg, rgba(0, 0, 0,0.03) 0%, rgba(0, 0, 0,0.03) 10%,rgba(0, 0, 0,0.03) 10%, rgba(0, 0, 0,0.03) 25%,rgba(30, 30, 30,0.03) 25%, rgba(30, 30, 30,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(92deg, rgba(237, 237, 237,0.03) 0%, rgba(237, 237, 237,0.03) 10%,rgba(66, 66, 66,0.03) 10%, rgba(66, 66, 66,0.03) 25%,rgba(10, 10, 10,0.03) 25%, rgba(10, 10, 10,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(46deg, rgba(231, 231, 231,0.03) 0%, rgba(231, 231, 231,0.03) 10%,rgba(33, 33, 33,0.03) 10%, rgba(33, 33, 33,0.03) 25%,rgba(37, 37, 37,0.03) 25%, rgba(37, 37, 37,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(176deg, rgba(125, 125, 125,0.01) 0%, rgba(125, 125, 125,0.01) 10%,rgba(210, 210, 210,0.01) 10%, rgba(210, 210, 210,0.01) 25%,rgba(112, 112, 112,0.01) 25%, rgba(112, 112, 112,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(100deg, rgba(70, 70, 70,0.01) 0%, rgba(70, 70, 70,0.01) 10%,rgba(46, 46, 46,0.01) 10%, rgba(46, 46, 46,0.01) 25%,rgba(203, 203, 203,0.01) 25%, rgba(203, 203, 203,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(304deg, rgba(100, 100, 100,0.01) 0%, rgba(100, 100, 100,0.01) 10%,rgba(50, 50, 50,0.01) 10%, rgba(50, 50, 50,0.01) 25%,rgba(196, 196, 196,0.01) 25%, rgba(196, 196, 196,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(186deg, rgba(40, 40, 40,0.02) 0%, rgba(40, 40, 40,0.02) 10%,rgba(224, 224, 224,0.02) 10%, rgba(224, 224, 224,0.02) 25%,rgba(62, 62, 62,0.02) 25%, rgba(62, 62, 62,0.02) 40%,transparent 40%, transparent 100%),linear-gradient(6deg, rgba(37, 37, 37,0.03) 0%, rgba(37, 37, 37,0.03) 10%,rgba(219, 219, 219,0.03) 10%, rgba(219, 219, 219,0.03) 25%,rgba(43, 43, 43,0.03) 25%, rgba(43, 43, 43,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(42deg, rgba(212, 212, 212,0.01) 0%, rgba(212, 212, 212,0.01) 10%,rgba(24, 24, 24,0.01) 10%, rgba(24, 24, 24,0.01) 25%,rgba(15, 15, 15,0.01) 25%, rgba(15, 15, 15,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(23deg, rgba(122, 122, 122,0.03) 0%, rgba(122, 122, 122,0.03) 10%,rgba(149, 149, 149,0.03) 10%, rgba(149, 149, 149,0.03) 25%,rgba(44, 44, 44,0.03) 25%, rgba(44, 44, 44,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(152deg, rgba(196, 196, 196,0.03) 0%, rgba(196, 196, 196,0.03) 10%,rgba(151, 151, 151,0.03) 10%, rgba(151, 151, 151,0.03) 25%,rgba(70, 70, 70,0.03) 25%, rgba(70, 70, 70,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(157deg, rgba(43, 43, 43,0.03) 0%, rgba(43, 43, 43,0.03) 10%,rgba(20, 20, 20,0.03) 10%, rgba(20, 20, 20,0.03) 25%,rgba(161, 161, 161,0.03) 25%, rgba(161, 161, 161,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(1deg, rgba(89, 89, 89,0.03) 0%, rgba(89, 89, 89,0.03) 10%,rgba(154, 154, 154,0.03) 10%, rgba(154, 154, 154,0.03) 25%,rgba(197, 197, 197,0.03) 25%, rgba(197, 197, 197,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(58deg, rgba(117, 117, 117,0.01) 0%, rgba(117, 117, 117,0.01) 10%,rgba(134, 134, 134,0.01) 10%, rgba(134, 134, 134,0.01) 25%,rgba(217, 217, 217,0.01) 25%, rgba(217, 217, 217,0.01) 40%,transparent 40%, transparent 100%),linear-gradient(47deg, rgba(55, 55, 55,0.03) 0%, rgba(55, 55, 55,0.03) 10%,rgba(97, 97, 97,0.03) 10%, rgba(97, 97, 97,0.03) 25%,rgba(4, 4, 4,0.03) 25%, rgba(4, 4, 4,0.03) 40%,transparent 40%, transparent 100%),linear-gradient(90deg, rgb(255,255,255),rgb(255,255,255));
                    }
                    .container {
                        max-width: 600px;
                        margin: 50px auto;
                        padding: 20px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        background-color:white;
                    }
                    input[type="text"], textarea {
                        width: 95%;
                        padding: 10px;
                        margin-bottom: 10px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        resize: none;
                    }
                    input[type="submit"] {
                        background-color: Orange;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    }
                    input[type="submit"]:hover {
                        background-color: #E9967A;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h4>answer</h4>
                    <div class="question-box">
                        <h5><?php echo $answer['title']; ?></h5>
                        <p><?php echo $answer['content']; ?></p>
                    </div>
                    <!-- Display the form for answering the question below -->
                    <h4>comment</h4>
                    <form method="post" action="create_comment.php?id=<?php echo $answerId; ?>">


                        <!-- Include any necessary form fields for the comment -->
                        <textarea name="content" rows="4" cols="50" placeholder="Type your comment here..." required></textarea><br>
                        <input type="submit" value="Submit comment">
                    </form>
                </div>
            </body>
        </html>
        <?php
    } else {
        echo "answer not found.";
    }
} else {
    echo "answer ID is not provided.";
}
?>