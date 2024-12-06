<?php
session_start();

include '../../../db_connection.php';

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
                    header("Location: ../../admin/admin.php");
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
    <div id="form_login">
        <form id="loginForm" method="POST" action="login.php">
            <div class="mb-4">
                <input type="text" id="username" name="username" placeholder="Email" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" class="w-full p-3 rounded bg-gray-700 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                <?php if ($fieldsError && empty($username)): ?>
                    <p class="text-red-400 mt-2">Email is required.</p>
                <?php elseif ($emailError): ?>
                    <p class="text-red-400 mt-2"><?php echo $emailError; ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <input type="password" id="password" name="password" placeholder="Password" class="w-full p-3 rounded bg-gray-700 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                <?php if ($fieldsError && empty($password)): ?>
                    <p class="text-red-400 mt-2">Password is required.</p>
                <?php elseif ($passwordError): ?>
                    <p class="text-red-400 mt-2"><?php echo $passwordError; ?></p>
                <?php endif; ?>
            </div>
            
            <button class="w-full py-3 bg-blue-500 hover:bg-blue-400 transition rounded text-white font-bold">Log in</button>
        </form>
        <div class="text-center mt-4 text-white">
    <p>Don't have an account? 
    <a href="register.php" class="text-blue-400 hover:text-blue-500 transition">Sign up</a>        
    </p>
</div>
    </div>
</div>



</body>
</html>

