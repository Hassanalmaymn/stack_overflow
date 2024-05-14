<?php
session_start();
$active = "question page";
require_once 'necessary/dbcon.php';

// Function to get a question by its ID
function getQuestionById($questionId) {
    $conn = dbcon();
    if (!$conn) {
        return null; // Return null if connection fails
    }

    // Prepare SQL query to fetch question by ID
    $stmt = $conn->prepare("SELECT * FROM question WHERE id = ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return null; // Return null if query preparation fails
    }

    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch question details
    $question = $result->fetch_assoc();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $question;
}

// Function to insert an answer into the database
function insertComment($questionId, $userId, $content) {
    $conn = dbcon();
    if (!$conn) {
        return false; // Return false if connection fails
    }

    // Prepare SQL statement to insert the answer
    $stmt = $conn->prepare("INSERT INTO comment_answer (userid, questionid, content) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return false; // Return false if query preparation fails
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("sss", $userId, $questionId, $content);
    $result = $stmt->execute();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $result; // Return true if insertion is successful, false otherwise
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $questionId = $_GET['id'];
    $userId = $_COOKIE['user_id'];
    $content = $_POST['content'];

    // Insert the answer into the database
    $success = insertComment($questionId, $userId, $content);

    if ($success) {
        // Redirect back to the question page or display a success message
        header("Location: question.php?id=$questionId");
        exit;
    } else {
        // Handle the case where answer insertion fails
        echo "Failed to submit the comment.";
    }
}


// Retrieve the question details based on the question ID
if(isset($_GET['id'])) {
    $questionId = $_GET['id'];
    // Retrieve the question details based on the question ID
    $question = getQuestionById($questionId);

    // Display the question details
    if($question) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>comment for Question</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="icon" href="icon2.png">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 50px auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
                <h4>Question</h4>
                <div class="question-box">
                    <h5><?php echo $question['title']; ?></h5>
                    <p><?php echo $question['content']; ?></p>
                </div>
                <!-- Display the form for answering the question below -->
                <h4>comment</h4>
                    <form method="post" action="create_comment_forQuestion.php?id=<?php echo $questionId; ?>">


                        <!-- Include any necessary form fields for the comment -->
                        <textarea name="content" rows="4" cols="50" placeholder="Type your comment here..." required></textarea><br>
                        <input type="submit" value="Submit comment">
                    </form>
                </div>
            </body>
        </html>
        <?php
         } else {
            echo "Question not found.";
        }
    } else {
        echo "Question ID is not provided.";
    }
    ?>