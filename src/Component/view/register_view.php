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
<body class="bg-cover bg-center h-screen flex items-center justify-center" style="background-image: url('../image/tlo_register.png');">
    <div class="bg-gray-900 bg-opacity-75 text-white p-10 rounded-lg shadow-lg w-full max-w-md">
        <div id="logo" class="text-center mb-6">
            <h1 class="text-3xl font-bold"><a href="index.php" class="text-white hover:text-blue-400 transition">HobbyHub</a></h1>
        </div>
        <div id="form_register">
            <form id="registerForm" method="POST" action="register.php">
                <div class="mb-4">
                    <input type="text" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" class="w-full p-3 rounded bg-gray-700 bg-opacity-50 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                    <?php if ($emailError): ?>
                        <p class="text-red-400 mt-2"><?php echo $emailError; ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <input type="text" id="fullname" name="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($_POST['fullname'] ?? ''); ?>" class="w-full p-3 rounded bg-gray-700 bg-opacity-50 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                    <?php if ($fullnameError): ?>
                        <p class="text-red-400 mt-2"><?php echo $fullnameError; ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <input type="text" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" class="w-full p-3 rounded bg-gray-700 bg-opacity-50 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                    <?php if ($usernameError): ?>
                        <p class="text-red-400 mt-2"><?php echo $usernameError; ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <input type="password" id="password" name="password" placeholder="Password" class="w-full p-3 rounded bg-gray-700 bg-opacity-50 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
                    <?php if ($passwordError): ?>
                        <p class="text-red-400 mt-2"><?php echo $passwordError; ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="w-full p-3 rounded bg-gray-700 bg-opacity-50 border border-gray-600 focus:border-blue-400 focus:ring-blue-400">
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
