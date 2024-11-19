<?php
session_start(); // Start the session to store user data

// Include database connection
include 'db_connection.php'; // Assuming you have the connection set up in this file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = $_POST['username']; // Email entered by the user
    $password = $_POST['password']; // Password entered by the user

    // Sanitize user input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if user exists with the given email
    $sql = "SELECT * FROM users WHERE email = '$username'";

    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // User found, now verify password
        $user = mysqli_fetch_assoc($result);

        // Verify the password using password_hash()
        if ($password == $user['password_hash']) {
            // Password is correct, log the user in
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to homepage or dashboard
            header("Location: dashboard.php"); // Adjust to your homepage/dashboard URL
            exit;
        } else {
            // Incorrect password
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        // No user found with that email
        $error_message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login • HobbyHub</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div id="container">
    <div id="logo">
        <h1>HobbyHub</h1>
    </div>
    <div id="form_login">
        <form method="POST" action="login.php">
            <input type="text" id="username" name="username" placeholder="Email" required><br>
            <input type="password" id="password" name="password" placeholder="Password" required><br>
            <button class="button-10" role="button">Log in</button>
        </form>

        <!-- Display error message if login failed -->
        <?php
        if (isset($error_message)) {
            echo "<p style='color:red;'>$error_message</p>";
        }
        ?>
    </div>
</div>

<div id="container_register">
    <p>Don't have an account? 
    <a href="register.php">Sign up</a>        
    </p>
</div>

</body>
</html>