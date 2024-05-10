<?php

session_start();
require_once 'User.php';
require_once 'necessary/dbcon.php';
//require_once 'question.php';

if (!isLoggedIn()) {
    header("Location: Signin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer_id'], $_POST['title'], $_POST['content'])) {
    $answer_id = $_POST['answer_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Update the question
    $db = dbcon();
    $stmt = $db->prepare("UPDATE answer SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $answer_id);

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
