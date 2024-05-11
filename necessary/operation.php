<?php

require_once 'dbcon.php';

function go($page) {
    echo "<script>window.location.href='" . $page . "'</script>";
}

function get10recentquestion() {
    $db = dbcon();

    $sql = "SELECT title,content,time,stack_user.id AS uid ,"
            . "name, question.id AS qid FROM question,stack_user where"
            . " stack_user.id=question.userid ORDER BY question.time DESC  ;";
    $result = mysqli_query($db, $sql);

    $assocq = array();
    while ($row = mysqli_fetch_array($result)) {
        $assocq[] = $row;
    }
    return $assocq;
}

function get10questionwithmostanswers() {
    $db = dbcon();

    $sql = "SELECT stack_user.name,question.userid,question.id AS qid,question.title,question.content,question.time,COUNT(answer.id)
       AS numberofanswers FROM ((question LEFT JOIN answer ON question.id=answer.questionid) JOIN stack_user ON stack_user.id=question.userid) 
            GROUP BY question.id ORDER BY COUNT(answer.id) DESC ;  ";

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

function find($search) {
    $db = dbcon();

    $sql = "SELECT question.id AS qid,question.title,question.content,stack_user.name,question.time, COUNT(answer.id)
 AS numberofanswers FROM ((stack_user join question ON stack_user.id=question.userid) LEFT JOIN answer ON answer.questionid=question.id) 
 WHERE question.id IN 
 (SELECT question.id FROM question WHERE question.title LIKE '%" . $search . "%' OR  question.content LIKE '%" . $search . "%') GROUP BY
  question.id ORDER by numberofanswers DESC ;";

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

function getthequestionanswers($question_id) {
    $db = dbcon();

    $sql = "SELECT answer.userid,answer.title,answer.content,answer.time AS answertime,stack_user.name,answer.id "
            . "FROM question,answer,stack_user WHERE question.id=answer.questionid AND answer.userid=stack_user.id AND question.id=" . $question_id . ";  ";

    $result = mysqli_query($db, $sql);
    $assocq = array();
    while ($row = mysqli_fetch_array($result)) {

        $assocq[] = $row;
    }
    return $assocq;
}

function searchuser($search, $userid) {
    $db = dbcon();

    $sql = "SELECT question.id AS qid,question.title,question.content,stack_user.name,question.time, COUNT(answer.id)
 AS numberofanswers FROM ((stack_user join question ON stack_user.id=question.userid) LEFT JOIN answer ON answer.questionid=question.id) 
 WHERE question.id IN (SELECT question.id FROM question WHERE question.title LIKE '%" . $search . "%' OR  question.content LIKE '%" . $search . "%')
 AND stack_user.id=" . $userid . " GROUP BY
  question.id ORDER by numberofanswers DESC ;";
    $result = mysqli_query($db, $sql);
    $assocq = array();
    while ($row = mysqli_fetch_array($result)) {

        $assocq[] = $row;
    }
    return $assocq;
}

function findmyanswer($search, $userid) {
    $db = dbcon();

    $sql = "SELECT stack_user.name,answer.time,answer.content,answer.title,answer.id FROM answer,stack_user WHERE answer.userid=stack_user.id "
            . "AND stack_user.id='" . $userid . "' AND answer.id IN (SELECT answer.id FROM answer WHERE answer.title LIKE '%" . $search . "%' OR answer.content LIKE '%" . $search . "%') ;";

    $result = mysqli_query($db, $sql);
    $assocq = array();
    while ($row = mysqli_fetch_array($result)) {

        $assocq[] = $row;
    }
    return $assocq;
}

function getAverageRate($answerId) {
    $conn = dbcon();
    if (!$conn) {
        return null; // Return null if connection fails
    }

    // Prepare SQL query to calculate the average rate
    $stmt = $conn->prepare("SELECT AVG(rate) AS average_rate FROM rate WHERE answerid = ?");
    if (!$stmt) {
        echo "Error: " . $conn->error;
        return null; // Return null if query preparation fails
    }

    $stmt->bind_param("i", $answerId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the average rate
    $row = $result->fetch_assoc();
    $averageRate = $row['average_rate'];

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $averageRate;
}
