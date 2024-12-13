<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Profile • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>

<main>
 <section class="py-10 my-auto dark:bg-gray-900">
    <div class="lg:w-[80%] md:w-[90%] xs:w-[96%] mx-auto flex gap-4">
        <div class="lg:w-[88%] md:w-[80%] sm:w-[88%] xs:w-full mx-auto shadow-2xl p-4 rounded-xl h-fit self-center dark:bg-gray-800/40 animate__fadeIn">
            <!-- Komunikaty o błędach i sukcesach -->
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
            }
            ?>

            <?php if ($message_text): ?>
                <div class="<?php echo $message_class; ?> mt-6">
                    <p><?php echo htmlspecialchars($message_text); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Sekcja zdjęcia profilowego -->
                <div class="w-full rounded-sm bg-cover bg-center bg-no-repeat items-center">
                    <div class="mx-auto flex justify-center w-[141px] h-[141px] bg-blue-300/20 rounded-full relative">
                        <!-- Wyświetlanie zdjęcia profilowego -->
                        <?php
                            $image_src = '/src/public/image/default.png';  // Zmienna z pełną ścieżką do default.png
                            ?>
                        <div class="w-full h-full rounded-full overflow-hidden">
                            <?php if ($user['profile_picture'] && $user['profile_picture'] !== 'default.png'): ?>
                                <img id="profile_picture" class="w-full h-full object-cover transform transition duration-300 ease-in-out hover:scale-105" src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
                            <?php else: ?>
                                <img id="profile_picture" class="w-full h-full object-cover transform transition duration-300 ease-in-out hover:scale-105" src="<?php echo $image_src; ?>" alt="Default Profile Picture">
                            <?php endif; ?>
                        </div>

                        <!-- Ikona przesyłania zdjęcia -->
                        <div class="absolute bottom-0 right-0 flex space-x-2">
                            <div class="bg-white/90 rounded-full w-6 h-6 text-center mb-2 mr-2">
                                <input type="file" name="profile_picture" id="upload_profile" hidden>
                                <label for="upload_profile">
                                    <svg data-slot="icon" class="w-6 h-5 text-blue-700" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"></path>
                                    </svg>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center items-center mt-8">
    <button type="submit" name="reset_picture" class="bg-blue-500 text-white font-bold px-4 py-2 rounded-md hover:bg-blue-600 transition-transform transform hover:-translate-y-1">
        Reset Picture
    </button>
</div>


                <!-- Pola edytowalne -->
                <div class="flex gap-2 flex-wrap">
                    <div class="w-full sm:w-[48%] md:w-[48%] lg:w-[48%] mb-4">
                        <label for="username" class="mb-2 text-gray-200">Username</label>
                        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 ease-in-out">
                    </div>
                    <div class="w-full sm:w-[48%] md:w-[48%] lg:w-[48%] mb-4">
                        <label for="email" class="mb-2 text-gray-200">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 ease-in-out">
                    </div>
                    <div class="w-full sm:w-[48%] md:w-[48%] lg:w-[48%] mb-4">
                        <label for="full_name" class="mb-2 text-gray-200">Full Name</label>
                        <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" class="p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 ease-in-out">
                    </div>
                    <div class="w-full sm:w-[48%] md:w-[48%] lg:w-[48%] mb-4">
                        <label for="bio" class="mb-2 text-gray-200">Bio</label>
                        <textarea name="bio" id="bio" class="p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 ease-in-out"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                    </div>
                </div>

                <!-- Przycisk zapisz -->
                <div class="w-full rounded-lg bg-blue-500 mt-4 text-white text-lg font-semibold hover:bg-blue-600 transform transition duration-300 ease-in-out">
                    <button type="submit" class="w-full p-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</section>

</main>
</body>
</html>
