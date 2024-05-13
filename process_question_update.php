<?php

session_start();
require_once 'User.php';
require_once 'necessary/dbcon.php';

if (!isLoggedIn()) {
    header("Location: Signin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question_id'], $_POST['title'], $_POST['content'])) {
    $question_id = $_POST['question_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Update the question
    $db = dbcon();
    $stmt = $db->prepare("UPDATE question SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $question_id);

    if ($stmt->execute()) {
        header("Location: index.php?status=success"); // Redirect after successful update
    } else {
        echo "Failed to update the question.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>