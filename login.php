<?php
session_start(); // Start the session to store user data

// Include database connection
include 'db_connection.php'; // Assuming you have the connection set up in this file

// Initialize error messages
$emailError = '';
$passwordError = '';
$fieldsError = false; // Flag for empty fields

// Check if form is submitted
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = $_POST['username']; // Email entered by the user
    $password = $_POST['password']; // Password entered by the user

    // Sanitize user input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if any field is empty
    if (empty($username) || empty($password)) {
        $fieldsError = true;
    }

    // If the email is not empty, validate it
    if (!empty($username) && !filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format. Please include '@' in the email address.";
    }

    // If there are no errors, proceed with login attempt
    if (!$fieldsError && empty($emailError)) {
        // Query to check if user exists with the given email
        $sql = "SELECT * FROM users WHERE email = '$username'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            // User found, now verify password
            $user = mysqli_fetch_assoc($result);

            // Assuming the password is hashed in the database
            if ($password == $user['password_hash']){
                // Password is correct, log the user in
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to homepage or dashboard
                header("Location: glowna.php");
                exit;
            } else {
                // Incorrect password
                $passwordError = "Invalid password. Please try again.";
            }
        } else {
            // No user found with that email
            $emailError = "No account found with that email.";
        }
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

<!-- JavaScript to handle form validation and prevent page refresh -->
<script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    // Get all the form fields
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    let valid = true;

    // Reset error messages
    document.querySelectorAll('.error').forEach(function(error) {
        error.remove();
    });

    // Check if any field is empty
    if (!username || !password) {
        valid = false;
        if (!username) {
            showError('Email is required.', 'username');
        }
        if (!password) {
            showError('Password is required.', 'password');
        }
    }

    // Check if email is valid
    if (username && !validateEmail(username)) {
        valid = false;
        showError('Invalid email format.', 'username');
    }

    // If not valid, prevent form submission
    if (!valid) {
        event.preventDefault();
    }
});

// Email validation function
function validateEmail(email) {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return regex.test(email);
}

// Function to display error messages under specific field
function showError(message, fieldId) {
    const errorElement = document.createElement('p');
    errorElement.classList.add('error');
    errorElement.textContent = message;
    
    const field = document.getElementById(fieldId);
    field.insertAdjacentElement('afterend', errorElement);
}
</script>

</body>
</html>
