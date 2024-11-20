<?php
// Import the database connection file
require_once 'db_connection.php';

// Variables for error messages and success messages
$emailError = '';
$passwordError = '';
$confirmPasswordError = '';
$fullnameError = '';
$usernameError = '';
$successMessage = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $email = trim($_POST['email']);
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validate input
    if (empty($email)) {
        $emailError = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email address.";
    }

    if (empty($fullname)) {
        $fullnameError = "Full name is required.";
    }

    if (empty($username)) {
        $usernameError = "Username is required.";
    }

    if (empty($password)) {
        $passwordError = "Password is required.";
    } elseif ($password !== $confirmPassword) {
        $passwordError = "Passwords do not match.";
    }

    // If no errors, proceed to insert the user into the database
    if (empty($emailError) && empty($fullnameError) && empty($usernameError) && empty($passwordError)) {
        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Check if email or username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param('ss', $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $emailError = "Email or username is already taken.";
        } else {
            // Insert the user into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, full_name, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param('ssss', $username, $email, $passwordHash, $fullname);


            if ($stmt->execute()) {
                $successMessage = "<b>Registration successful! You can now log in.</b>";
                // Reset form fields
                $_POST = [];
            } else {
                die("Error inserting data: " . $conn->error);
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up • HobbyHub</title>
    <link rel="stylesheet" href="register.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div id="container">
    <div id="logo">
    <h1><a href="index.php">HobbyHub</a></h1>
    </div>
    <div id="form_register">
        <form id="registerForm" method="POST" action="register.php">
            <!-- E-mail -->
            <input type="text" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <?php if ($emailError): ?>
                <p class="error"><?php echo $emailError; ?></p>
            <?php endif; ?>

            <!-- Full Name -->
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($_POST['fullname'] ?? ''); ?>">
            <?php if ($fullnameError): ?>
                <p class="error"><?php echo $fullnameError; ?></p>
            <?php endif; ?>

            <!-- Username -->
            <input type="text" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            <?php if ($usernameError): ?>
                <p class="error"><?php echo $usernameError; ?></p>
            <?php endif; ?>

            <!-- Password -->
            <input type="password" id="password" name="password" placeholder="Password">
            <?php if ($passwordError): ?>
                <p class="error"><?php echo $passwordError; ?></p>
            <?php endif; ?>

            <!-- Confirm Password -->
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
            <?php if ($confirmPasswordError): ?>
                <p class="error"><?php echo $confirmPasswordError; ?></p>
            <?php endif; ?>

            <button class="button-10" role="button" type="submit">Sign up</button>
        </form>

        <?php if ($successMessage): ?>
            <p class="success"><?php echo $successMessage; ?></p>
        <?php endif; ?>
    </div>
</div>

<div id="container_register">
    <p>Already have an account? <a href="login.php">Log in</a></p>
</div>

</body>
</html>
