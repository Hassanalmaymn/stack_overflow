<?php
// Include database connection and user authentication functions
require_once 'dbcon.php';
require_once 'user.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (login($email, $password)) {
        // Redirect to dashboard or home page upon successful login
        header("Location: dashboard.php");
        exit();
    } else {
        // Display error message if login fails
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styleee.css">
</head>
<body>
    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
            <input type="submit" class="btn" value="Sign In" name="signIn">
            <?php if (isset($error_message)) { ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php } ?>
        </form>
        <br>
        <div class="links">
            <p>Don't have an account yet?</p>
            <a href="signup.php">Sign Up</a>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>