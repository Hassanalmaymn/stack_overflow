<?php

require_once 'necessary/dbcon.php'; // Assuming your database connection function is in dbcon.php

function register($name, $email, $password)
{
    $db = dbcon();
    
    if (!$db) {
        return "Failed to connect to the database.";
    }
    
    $check_sql = "SELECT COUNT(*) as count FROM stack_user WHERE email = ? OR name = ?";
    $check_stmt = mysqli_prepare($db, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $email, $name);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (!$check_result) {
        return "Error: " . mysqli_error($db);
    }
    
    $row = mysqli_fetch_assoc($check_result);
    if ($row['count'] > 0) {
        return "User already exists";
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO stack_user (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);
    
    if (mysqli_stmt_execute($stmt)) {
        $user_id = mysqli_insert_id($db);
        setcookie('user_id', $user_id, time() + (86400 * 30), "/");
        return true;
    } else {
        return "Error: " . mysqli_error($db);
    }
}

function login($username, $password)
{
    $db = dbcon();
    $sql = "SELECT id, password FROM stack_user WHERE name = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];
        if (password_verify($password, $hashed_password)) {
            $user_id = $row['id'];
            setcookie('user_id', $user_id, time() + (86400 * 30), "/");
            return true;
        }
    }
    return false;
}

function isLoggedIn()
{
    return isset($_COOKIE['user_id']);
}

function logout()
{
    setcookie('user_id', '', time() - 3600, '/');
}

?>
