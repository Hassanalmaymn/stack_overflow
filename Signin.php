<?php
session_start();
require_once 'User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; // Assuming username is used for login
    $password = $_POST['password'];
    if (login($username, $password)) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon2.png">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/styleSign.css">
</head>
<body>
    <div class="container" id="signIn">
        <a href="index.php"><img src="SignLogo.jpg" alt="Logo" width="250" height="70" style="margin-left: 80px;"></a>

        <h1 class="form-title">Sign In</h1>
        <?php if (isset($error_message)){ echo "<div class='alert alert-danger d-flex gap-2'><i class='bi bi-info-circle-fill'></i><p class='m-0'>". $error_message . '</p></div>';} ?>
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