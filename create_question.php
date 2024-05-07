<?php

require_once 'User.php';


if (!isLoggedIn()) {
    header("Location: Signin.php");
    exit(); 
}

require_once 'necessary/dbcon.php';

// Define a variable to store the alert message
$alert_message = '';

// This block of PHP code below is where you could handle form submission, validation, etc.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $title = $_POST['title'];
    $comment = $_POST['comment'];

    // Establish connection to the database
    $conn = dbcon();

    // Prepare and execute SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO question (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $comment);

    // Check if the insertion was successful
    if ($stmt->execute()) {
        $alert_message = '<div class="alert alert-success" role="alert">Question submitted successfully!</div>';
    } else {
        $alert_message = '<div class="alert alert-danger" role="alert">Error submitting question. Please try again.</div>';
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Question</title>
<!-- Bootstrap CSS -->
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
    <h4>Write Your Question here to Share it with the community and get helpful answers!</h4>
    <!-- Display the alert message -->
    <?php echo $alert_message; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="comment" placeholder="Your Question" rows="6" required></textarea>
        <input type="submit" value="Submit">
    </form>
</div>

</body>
</html>
