<?php

require_once 'dbcon.php';

function go($page)
{
echo "<script>window.location.href='" . $page . "'</script>";
}
function get10recentquestion(){
$db = dbcon();

$sql = "SELECT * FROM question ORDER BY question.time DESC LIMIT 10 ;";
$result = mysqli_query($db, $sql);

return $result;

}
function get10questionwithmostanswers(){
$db = dbcon();

$sql = "SELECT question.id ,question.title,question.content,COUNT(*) AS numofans FROM question ,answer WHERE question.id=answer.questionid 
 GROUP BY question.id ORDER BY numofans DESC  LIMIT 10 ;";
$result = mysqli_query($db, $sql);

return $result;

}
function getuserquestions($userid){
$db = dbcon();

$sql = "SELECT title,content FROM stack_user,question WHERE stack_user.id=question.userid AND stack_user.id=".$userid.";";
$result = mysqli_query($db, $sql);

return $result;

}


function getuseranswers($userid){
$db = dbcon();

$sql = "SELECT title,content FROM stack_user,answer WHERE stack_user.id=answer.userid AND stack_user.is=".$userid.";";
$result = mysqli_query($db, $sql);

return $result;

}