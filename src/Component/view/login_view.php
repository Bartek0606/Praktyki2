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
