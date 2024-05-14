<?php
session_start();
require_once 'User.php';
require_once 'necessary/dbcon.php';
$active = "answer page";

if (!isLoggedIn()) {
    header("Location: Signin.php");
    exit;
}

// Fetch the question ID either from GET or from session as fallback
$answer_id = $_POST['answer_id'];
//isset($_POST['answer_id']) ? $_POST['answer_id'] : (isset($_SESSION['answer_id']) ? $_SESSION['answer_id'] : null);

/* if (!$answer_id || !is_numeric($answer_id)) {
  echo "No answer ID provided or invalid ID.";
  exit;
  } */

// Fetch question data
$answer = get_TheAnswer($answer_id);
if (empty($answer)) {
    echo "No answer found for the provided ID.";
    exit;
}

// Function to fetch the question details
function get_TheAnswer($answer_id) {
    $db = dbcon();
    $stmt = $db->prepare("SELECT * FROM answer WHERE answer.id = ?");
    $stmt->bind_param("i", $answer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="icon2.png">
        <title>Edit answer</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            <h4>Edit Your answer</h4>
            <form action="process_answer_update.php" method="post">
                <input type="hidden" name="answer_id" value="<?php echo $answer['id']; ?>">
                <input type="text" name="title" value="<?php echo htmlspecialchars($answer['title']); ?>" required>
                <textarea name="content" rows="6" required><?php echo htmlspecialchars($answer['content']); ?></textarea>
                <input type="submit" name="submit" value="Update answer">
            </form>
        </div>
    </body>
</html>