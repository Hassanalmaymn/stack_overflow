<?php
session_start();
require_once 'User.php'; // Include the file containing user functions

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email= $_POST['email'];
    $password = $_POST['password'];
    $db = dbcon(); // Establish database connection
    if ($db) {
        register($name, $email, $password); // Call the register function from User.php
    } else {
        echo "Failed to connect to the database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles/styleSign.css">
</head>
<body>
    <div class="container" id="signup">
        <h1 class="form-title">Sign Up</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" id="name" placeholder="Name" required>
                <label for="name">Username</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
        </form>
        <br>
        <div class="links">
            <p>Already Have Account?</p>
            <button onclick="window.location.href = 'Signin.php';">Sign In</button>
        </div>
    </div>
</body>
</html>
