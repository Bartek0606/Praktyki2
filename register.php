<?php
// Error messages
$emailError = '';
$passwordError = '';
$confirmPasswordError = '';
$fullnameError = '';
$usernameError = '';
$fieldsError = false; // Flag indicating if any field is empty

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if fields are empty
    if (empty($_POST['email'])) {
        $emailError = "Email is required.";
    }
    if (empty($_POST['fullname'])) {
        $fullnameError = "Full name is required.";
    }
    if (empty($_POST['username'])) {
        $usernameError = "Username is required.";
    }
    if (empty($_POST['password'])) {
        $passwordError = "Password is required.";
    }
    if (empty($_POST['confirm_password'])) {
        $confirmPasswordError = "Confirm password is required.";
    }

    // Email validation
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email address.";
    }

    // Password validation
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $passwordError = "Passwords do not match.";
    }

    // If there are no errors, process the data (e.g., save to the database)
    if (empty($emailError) && empty($passwordError) && empty($fullnameError) && empty($usernameError) && empty($confirmPasswordError)) {
        // Here you can save the user to the database
        // For now, just show a success message
        echo "Registration successful!";
        // After successful registration, do not refresh the page
        exit;
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
        <h1>HobbyHub</h1>
    </div>
    <div id="form_register">
        <form id="registerForm" method="POST" action="register.php">
            <!-- E-mail -->
            <input type="text" id="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            <?php if ($emailError): ?>
                <p class="error"><?php echo $emailError; ?></p>
            <?php endif; ?>

            <!-- Full Name -->
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : ''; ?>">
            <?php if ($fullnameError): ?>
                <p class="error"><?php echo $fullnameError; ?></p>
            <?php endif; ?>

            <!-- Username -->
            <input type="text" id="username" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
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
    </div>
</div>

<div id="container_register">
    <p>Already have an account? <a href="login.php">Log in</a></p>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form from submitting (page refresh)
    
    const email = document.getElementById('email').value;
    const fullname = document.getElementById('fullname').value;
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    let valid = true;

    // Reset error messages
    document.querySelectorAll('.error').forEach(function(error) {
        error.remove();
    });

    // Check if any field is empty
    if (!email) {
        valid = false;
        showError('Email is required.', 'email');
    }

    if (!fullname) {
        valid = false;
        showError('Full name is required.', 'fullname');
    }

    if (!username) {
        valid = false;
        showError('Username is required.', 'username');
    }

    if (!password) {
        valid = false;
        showError('Password is required.', 'password');
    }

    if (!confirmPassword) {
        valid = false;
        showError('Confirm password is required.', 'confirm_password');
    }

    // Check if email is valid
    if (email && !validateEmail(email)) {
        valid = false;
        showError('Invalid email address.', 'email');
    }

    // Check if passwords match
    if (password && confirmPassword && password !== confirmPassword) {
        valid = false;
        showError('Passwords do not match.', 'password');
    }

    // If everything is valid, submit the form via AJAX
    if (valid) {
        submitForm();
    }
});

// Email validation function
function validateEmail(email) {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return regex.test(email);
}

// Function to display error messages
function showError(message, fieldId) {
    const errorElement = document.createElement('p');
    errorElement.classList.add('error');
    errorElement.textContent = message;
    const field = document.getElementById(fieldId);
    field.insertAdjacentElement('afterend', errorElement);
}

// Function to submit the form via AJAX
function submitForm() {
    const form = document.getElementById('registerForm');
    const formData = new FormData(form);

    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Redirect to index.php after successful registration
        window.location.href = 'index.php'; // Redirect to homepage after registration
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

</body>
</html>
