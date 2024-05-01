<?php

require_once 'dbcon.php'; // Assuming your database connection function is in dbcon.php

function register($name, $email, $password)
{
    $db = connectDB();
    $hashed_password = md5($password); // Note: MD5 hashing is not secure, consider using stronger hashing algorithms like bcrypt or Argon2
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
    if (mysqli_query($db, $sql)) {
        $user_id = mysqli_insert_id($db);
        setcookie('user_id', $user_id, time() + (86400 * 30), "/"); // Set cookie for user ID
        return true;
    } else {
        return false;
    }
}

function login($email, $password)
{
    $db = connectDB();
    $hashed_password = md5($password); // Note: MD5 hashing is not secure, consider using stronger hashing algorithms like bcrypt or Argon2
    $sql = "SELECT id FROM users WHERE email = '$email' AND password = '$hashed_password'";
    $result = mysqli_query($db, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        setcookie('user_id', $user_id, time() + (86400 * 30), "/"); // Set cookie for user ID
        return true;
    } else {
        return false;
    }
}

function isLoggedIn()
{
    return isset($_COOKIE['user_id']); // Check if user is logged in based on existence of user_id cookie
}

function logout()
{
    setcookie('user_id', '', time() - 3600, '/'); // Clear user_id cookie to log out user
}

?>
