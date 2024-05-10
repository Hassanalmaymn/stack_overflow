<?php

require_once 'necessary/dbcon.php';

// Function to insert/update rating in the database
function rateAnswer($answerId, $questionid, $userId, $rating) {
    $conn = dbcon();
    if (!$conn) {
        return false; // Return false if connection fails
    }

    // Check if the user has already rated this answer
    $stmt_check = $conn->prepare("SELECT * FROM rate WHERE answerid = ? AND userid = ?");
    $stmt_check->bind_param("ii", $answerId, $userId);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // If the user has already rated, update the existing rating
        $stmt_update = $conn->prepare("UPDATE rate SET rate = ? WHERE answerid = ? AND userid = ?");
        $stmt_update->bind_param("iii", $rating, $answerId, $userId);
        $result = $stmt_update->execute();
        $stmt_update->close();
    } else {
        // If the user hasn't rated yet, insert a new rating
        $stmt_insert = $conn->prepare("INSERT INTO rate (userid, answerid, questionid, rate) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("iiii", $userId, $answerId, $questionid, $rating);
        $result = $stmt_insert->execute();
        $stmt_insert->close();
    }

    // Close statement and database connection
    $stmt_check->close();
    $conn->close();

    return $result; // Return true on success, false on failure
}


// Check if rating data is received
if (isset($_POST['answer_id'], $_POST['questionid'], $_POST['user_id'], $_POST['rating'])) {
    $answerId = $_POST['answer_id'];
    $questionid = $_POST['questionid'];
    $userId = $_POST['user_id'];
    $rating = $_POST['rating'];

    // Call the rateAnswer function to insert/update rating
    $success = rateAnswer($answerId, $questionid, $userId, $rating);

    if ($success) {
        echo "Rating saved successfully.";
    } else {
        echo "Failed to save rating.";
    }
} else {
    echo "Rating data is missing.";
}
?>



