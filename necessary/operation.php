<?php

require_once 'dbcon.php';

function go($page) {
    echo "<script>window.location.href='" . $page . "'</script>";
}

function get10recentquestion() {
    $db = dbcon();

    $sql = "SELECT title,content,time,stack_user.id AS uid ,"
            . "name, question.id AS qid FROM question,stack_user where"
            . " stack_user.id=question.userid ORDER BY question.time DESC LIMIT 10 ;";
    $result = mysqli_query($db, $sql);

    $assocq = array();
    while ($row = mysqli_fetch_array($result)) {
        $assocq[] = $row;
    }
    return $assocq;
}

function get10questionwithmostanswers() {
    $db = dbcon();

    $sql = "SELECT stack_user.name,question.userid,question.id,question.title,question.content,question.time,COUNT(answer.id)
       AS numberofanswers FROM ((question LEFT JOIN answer ON question.id=answer.questionid) JOIN stack_user ON stack_user.id=question.userid) 
            GROUP BY question.id ORDER BY COUNT(answer.id) DESC LIMIT 10 ;  ";

    $result = mysqli_query($db, $sql);
    $assocq = array();
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

    $sql = "SELECT question.id AS qid,question.title,question.content,stack_user.name,question.time, COUNT(answer.id)
       AS numberofanswers "
            . "FROM ((question LEFT JOIN answer ON question.id=answer.questionid) JOIN stack_user ON stack_user.id=question.userid)" 
            . "WHERE question.title LIKE '%" . $search . "%' OR question.content LIKE '%" . $search . "%'"
            . "GROUP BY question.id ORDER BY COUNT(answer.id) DESC  ;";
    $result = mysqli_query($db, $sql);
    $assocq = array();
    while ($row = mysqli_fetch_array($result)) {

        $assocq[] = $row;
    }
    return $assocq;
}

function getthequestion($question_id) {
    $db = dbcon();

    $sql = "SELECT stack_user.name,question.userid,question.id,question.title,"
            . "question.content,question.time FROM stack_user,question WHERE stack_user.id=question.userid AND question.id='" . $question_id . "';  ";

    $result = mysqli_query($db, $sql);
    $assocq = array();
    while ($row = mysqli_fetch_array($result)) {

        $assocq[] = $row;
    }
    return $assocq;
}
