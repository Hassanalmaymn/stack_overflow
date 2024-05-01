<?php
// Include database connection and user registration function
require_once 'dbcon.php';
require_once 'user.php';

// Initialize variables
$username = $email = $password = "";
$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Attempt to register user
    if (register($username, $email, $password)) {
        // Redirect to dashboard or home page upon successful registration
        header("Location: dashboard.php");
        exit();
    } else {
        // Display error message if registration fails
        $error_message = "Registration failed. Please try again.";
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
    <link rel="stylesheet" href="styleee.css">
</head>
<body>
    <div class="container" id="signup">
        <h1 class="form-title">Sign Up</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>" required>
                <label for="username">Username</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
            <?php if (!empty($error_message)) { ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php } ?>
        </form>
        <br>
        <div class="links">
            <p>Already Have Account?</p>
            <a href="signin.php">Sign In</a>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>