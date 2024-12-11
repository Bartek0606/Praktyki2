<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../edit_profile.css">
    <script src="../js/profile.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Profile • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main>
    <div class="container mx-auto p-6">
         <!-- Wiadomość o błędach i sukcesie -->
         <?php
        $message_class = '';
        $message_text = '';

        if (isset($_SESSION['error_message'])) {
            $message_class = 'bg-red-500 p-4 rounded-lg text-white'; 
                $message_text = $_SESSION['error_message'];
                unset($_SESSION['error_message']);
            } elseif (isset($_SESSION['success_message'])) {
                $message_class = 'bg-green-500 p-4 rounded-lg text-white'; 
                $message_text = $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                unset($_SESSION['success_message_changes']);
            }
        ?>

        <?php if ($message_text): ?>
            <div class="<?php echo $message_class; ?> mt-6">
                <p><?php echo htmlspecialchars($message_text); ?></p>
                <?php if (isset($_SESSION['success_message_changes'])): ?>
                    <div class="mt-4 bg-gray-700 p-4 rounded-lg">
                        <strong>Changes made:</strong>
                        <p><?php echo $_SESSION['success_message_changes']; ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg space-y-8">
            <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Formularz edycji -->
                <div class="space-y-4">
                    <div class="bg-gray-800 p-4 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <?php
                            $image_src = '/src/public/image/default.png';  // Zmienna z pełną ścieżką do default.png
                            ?>

                            <div class="w-24 h-24 rounded-full overflow-hidden">
                                <?php if ($user['profile_picture'] && $user['profile_picture'] !== 'default.png'): ?>
                                    <img class="w-full h-full object-cover" src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
                                <?php else: ?>
                                    <img class="w-full h-full object-cover" src="<?php echo $image_src; ?>" alt="Default Profile Picture">
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center space-x-2">
                                <div class="w-80">
                                    <label for="profile_picture" class="block mb-2">Profile Picture</label>
                                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="bg-gray-700 p-2 rounded-lg w-full text-gray-300 h-10">
                                </div>
                                <button type="submit" name="reset_picture" class="mt-8 bg-orange-400 p-2 rounded-full text-white hover:bg-gray-500 h-10 w-40">Reset Profile Picture</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pozostałe pola formularza -->
                <div class="space-y-4">
                    <div class="bg-gray-800 p-4 rounded-lg">
                        <label for="username" class="block mb-2">Username</label>
                        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="bg-gray-700 p-2 rounded-lg w-full text-gray-300">
                    </div>

                    <div class="bg-gray-800 p-4 rounded-lg">
                        <label for="email" class="block mb-2">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="bg-gray-700 p-2 rounded-lg w-full text-gray-300">
                    </div>

                    <div class="bg-gray-800 p-4 rounded-lg">
                        <label for="full_name" class="block mb-2">Full Name</label>
                        <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" class="bg-gray-700 p-2 rounded-lg w-full text-gray-300">
                    </div>

                    <div class="bg-gray-800 p-4 rounded-lg">
                        <label for="bio" class="block mb-2">Bio</label>
                        <textarea name="bio" id="bio" class="bg-gray-700 p-2 rounded-lg w-full text-gray-300"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                    </div>
                </div>

                <button type="submit" class="bg-orange-400 p-2 rounded-full text-white hover:bg-gray-500 w-full">Save Changes</button>
            </form>
        </div>

       
    </div>
</main>
</body>
</html>
