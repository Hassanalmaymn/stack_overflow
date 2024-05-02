<?php
require_once 'User.php'; // Include the file containing user functions

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (loginWithUsername($username, $password)) { // Call the login function from User.php
        // Redirect or display success message
        header("Location: index.php");
        exit();
    } else {
        // Display error message
        $error_message = "Invalid username or password.";
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
    <link rel="stylesheet" href="styleSign.css">
</head>
<body>
    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <?php if (isset($error_message)) echo '<div class="error-message">' . $error_message . '</div>'; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <div class="input-group">
              <i class="fas fa-user"></i>
              <input type="text" name="username" id="username" placeholder="Username" required>
              <label for="username">Username</label>
          </div>
          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
              <label for="password">Password</label>
          </div>
         <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <br>
        <div class="links">
          <p>Don't have an account yet?</p>
          <button onclick="window.location.href = 'Signup.php';">Sign Up</button>
        </div>
    </div>
</body>
</html>
