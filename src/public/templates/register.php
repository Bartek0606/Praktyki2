<?php

require_once '../../../db_connection.php';

$emailError = '';
$passwordError = '';
$confirmPasswordError = '';
$fullnameError = '';
$usernameError = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

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

    if (empty($emailError) && empty($fullnameError) && empty($usernameError) && empty($passwordError)) {
      
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param('ss', $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $emailError = "Email or username is already taken.";
        } else {
            
            // Domyślnie ustawiamy is_admin na 0 (zwykły użytkownik)
            $defaultProfilePicture = file_get_contents('../image/default.png'); 

            // Ustawienie is_admin na 0 (zwykły użytkownik)
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, full_name, created_at, profile_picture, is_admin) VALUES (?, ?, ?, ?, NOW(), ?, 0)");
            $stmt->bind_param('sssss', $username, $email, $passwordHash, $fullname, $defaultProfilePicture);

            if ($stmt->execute()) {
                $successMessage = "<b>Registration successful! You can now log in.</b>";
                
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
<script src="https://cdn.tailwindcss.com"></script>
<style>
  @keyframes slideDown {
    0% {
      transform: translateY(-20px);
      opacity: 0;
    }
    100% {
      transform: translateY(0);
      opacity: 1;
    }
  }
  .animate-slideDown {
    animation: slideDown 0.5s ease-out;
  }
</style>
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">
<div class="bg-gray-800 text-white p-10 rounded-lg shadow-lg animate-slideDown w-full max-w-md">
    <div id="logo" class="text-center mb-6">
        <h1 class="text-3xl font-bold"><a href="index.php" class="text-white hover:text-blue-400 transition">HobbyHub</a></h1>
    </div>
    <div id="form_register">
        <form id="registerForm" method="POST" action="register.php">
            <div class="mb-4">
                <input type="text" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" class="w-full p-3 rounded bg-gray-700 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                <?php if ($emailError): ?>
                    <p class="text-red-400 mt-2"><?php echo $emailError; ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <input type="text" id="fullname" name="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($_POST['fullname'] ?? ''); ?>" class="w-full p-3 rounded bg-gray-700 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                <?php if ($fullnameError): ?>
                    <p class="text-red-400 mt-2"><?php echo $fullnameError; ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <input type="text" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" class="w-full p-3 rounded bg-gray-700 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                <?php if ($usernameError): ?>
                    <p class="text-red-400 mt-2"><?php echo $usernameError; ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <input type="password" id="password" name="password" placeholder="Password" class="w-full p-3 rounded bg-gray-700 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                <?php if ($passwordError): ?>
                    <p class="text-red-400 mt-2"><?php echo $passwordError; ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="w-full p-3 rounded bg-gray-700 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                <?php if ($confirmPasswordError): ?>
                    <p class="text-red-400 mt-2"><?php echo $confirmPasswordError; ?></p>
                <?php endif; ?>
            </div>
            
            <button class="w-full py-3 bg-blue-500 hover:bg-blue-400 transition rounded text-white font-bold">Sign up</button>
        </form>
        <div class="text-center mt-4 text-white">
    <p>Already have an account? 
    <a href="login.php" class="text-blue-400 hover:text-blue-500 transition">Log in</a>        
    </p>
</div>

        <?php if ($successMessage): ?>
            <p class="text-green-400 mt-4"><?php echo $successMessage; ?></p>
        <?php endif; ?>
    </div>
</div>



</body>
</html>
