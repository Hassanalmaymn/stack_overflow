<?php
include 'dbinformation.php';
function dbcon(){
    $con=mysqli_connect(dbhost, dbusername, dbpwd ,dbname ,dbport);
    if (mysqli_errno($con)){
        die('error');
    }
    return $con;
}