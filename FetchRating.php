<?php
session_start();
require_once 'necessary/dbcon.php';

// Check if answer ID and user ID are received
if (isset($_POST['answer_id'], $_POST['user_id'])) {
    $answerId = $_POST['answer_id'];
    $userId = $_POST['user_id'];

    // Function to fetch user's rating for the given answer from the database
    function fetchUserRating($answerId, $userId) {
        $conn = dbcon();
        if (!$conn) {
            return ""; // Return empty string if connection fails
        }

        // Prepare SQL query to fetch user's rating for the answer
        $stmt = $conn->prepare("SELECT rate FROM rate WHERE answerid = ? AND userid = ?");
        $stmt->bind_param("ii", $answerId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the rating if it exists, otherwise return empty string
        if ($row = $result->fetch_assoc()) {
            return $row['rate'];
        } else {
            return "";
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    }

    // Call the fetchUserRating function and echo the result
    echo fetchUserRating($answerId, $userId);
} else {
    echo ""; // Return empty string if answer ID and user ID are not received
}
?>
