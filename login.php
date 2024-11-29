<?php
session_start();

include 'db_connection.php';

$emailError = '';
$passwordError = '';
$fieldsError = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $password = $_POST['password']; 

    // Sanity check for input
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    if (empty($username) || empty($password)) {
        $fieldsError = true;
    }

    if (!empty($username) && !filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format. Please include '@' in the email address.";
    }

    if (!$fieldsError && empty($emailError)) {
        // Fetch user data from the database
        $sql = "SELECT * FROM users WHERE email = '$username'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                
                // Check if the user is an admin
                if ($user['is_admin'] == 1) {
                    // Redirect to the admin page if the user is an admin
                    header("Location: admin/admin.php");
                } else {
                    // Otherwise, redirect to the normal page
                    header("Location: index.php");
                }
                exit;
            } else {
                $passwordError = "Invalid password. Please try again.";
            }
        } else {
            $emailError = "No account found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login • HobbyHub</title>
<link rel="stylesheet" href="login.css">
</head>
<body>

<div id="container">
    <div id="logo">
        <h1><a href="index.php">HobbyHub</a></h1>
    </div>
    <div id="form_login">
        <form id="loginForm" method="POST" action="login.php">
            <div>
                <input type="text" id="username" name="username" placeholder="Email" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
                <?php if ($fieldsError && empty($username)): ?>
                    <p class="error">Email is required.</p>
                <?php elseif ($emailError): ?>
                    <p class="error"><?php echo $emailError; ?></p>
                <?php endif; ?>
            </div>
            
            <div>
                <input type="password" id="password" name="password" placeholder="Password">
                <?php if ($fieldsError && empty($password)): ?>
                    <p class="error">Password is required.</p>
                <?php elseif ($passwordError): ?>
                    <p class="error"><?php echo $passwordError; ?></p>
                <?php endif; ?>
            </div>
            
            <button class="button-10" role="button">Log in</button>
        </form>
    </div>
</div>

<div id="container_register">
    <p>Don't have an account? 
    <a href="register.php">Sign up</a>        
    </p>
</div>

</body>
</html>
