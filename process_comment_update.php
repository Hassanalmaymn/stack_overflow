<?php

session_start();
require_once 'User.php';
require_once 'necessary/dbcon.php';
//require_once 'question.php';

if (!isLoggedIn()) {
    header("Location: Signin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_id'], $_POST['content'])) {
    $comment_id = $_POST['comment_id'];
    $content = $_POST['content'];

    // Update the question
    $db = dbcon();
    $stmt = $db->prepare("UPDATE comment_answer SET content = ? WHERE id = ?");
    $stmt->bind_param("si", $content, $comment_id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect after successful update
    } else {
        echo "Failed to update the question.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
