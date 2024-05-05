<?php

require_once 'dbcon.php';

function go($page) {
    echo "<script>window.location.href='" . $page . "'</script>";
}

function get10recentquestion() {
    $db = dbcon();

    $sql = "SELECT * FROM question ORDER BY question.time DESC LIMIT 10 ;";
    $result = mysqli_query($db, $sql);

        $assocq=array();
    while ($row = mysqli_fetch_array($result)) {
        $assocq[] = $row;
    }
    return $assocq;
    
}

function get10questionwithmostanswers() {
    $db = dbcon();

    $sql = "SELECT DISTINCT  question.title,question.content,question.time,COUNT(answer.id) FROM question,answer WHERE"
            . " question.id=answer.questionid ORDER BY (SELECT  COUNT(*) FROM answer"
            . " WHERE question.id=answer.questionid GROUP BY question.id) DESC LIMIT 10; ";
    $result = mysqli_query($db, $sql);
  $assocq=array();
    while ($row = mysqli_fetch_array($result)) {
        $assocq[] = $row;
    }
    return $assocq;
}

function getuserquestions($userid) {
    $db = dbcon();

    $sql = "SELECT title,content FROM stack_user,question WHERE stack_user.id=question.userid AND stack_user.id=" . $userid . ";";
    $result = mysqli_query($db, $sql);

    return $result;
}

function getuseranswers($userid) {
    $db = dbcon();

    $sql = "SELECT title,content FROM stack_user,answer WHERE stack_user.id=answer.userid AND stack_user.is=" . $userid . ";";
    $result = mysqli_query($db, $sql);

    return $result;
}

function search($search) {
    $db = dbcon();

    $sql = "SELECT title,content FROM stack_user,question,answer WHERE stack_user.id=answer.userid AND question.id=answer.questionid AND question.title LIKE '" . $search . "%' OR question.content LIKE '" . $search . "%';";
    $result = mysqli_query($db, $sql);

    return $result;
}
