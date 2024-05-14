<?php
session_start();
require_once 'User.php';
require_once 'necessary/dbcon.php';

if (!isLoggedIn()) {
    header("Location: Signin.php");
    exit;
}

// Fetch the question ID either from GET or from session as fallback
$question_id = isset($_POST['question_id']) ? $_POST['question_id'] : (isset($_SESSION['question_id']) ? $_SESSION['question_id'] : null);

if (!$question_id || !is_numeric($question_id)) {
    echo "No question ID provided or invalid ID.";
    exit;
}

// Fetch question data
$question = get_TheQuestion($question_id);
if (empty($question)) {
    echo "No question found for the provided ID.";
    exit;
}

// Function to fetch the question details
function get_TheQuestion($question_id) {
    $db = dbcon();
    $stmt = $db->prepare("SELECT stack_user.name, question.userid, question.id, question.title, question.content, question.time FROM stack_user JOIN question ON stack_user.id = question.userid WHERE question.id = ?");
    $stmt->bind_param("i", $question_id);
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
        <title>Edit Question</title>
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
            <h4>Edit Your Question</h4>
            <form action="process_question_update.php" method="post">
                <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                <input type="text" name="title" value="<?php echo htmlspecialchars($question['title']); ?>" required>
                <textarea name="content" rows="6" required><?php echo htmlspecialchars($question['content']); ?></textarea>
                <input type="submit" name="submit" value="Update Question">
            </form>
        </div>
    </body>
</html>
