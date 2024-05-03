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

$sql = "SELECT question.id ,question.title,COUNT(*) AS numofans FROM question ,answer WHERE question.id=answer.questionid 
 GROUP BY question.id ORDER BY numofans DESC  LIMIT 10 ;";
$result = mysqli_query($db, $sql);

return $result;

}


