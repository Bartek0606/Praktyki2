<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Create New Post • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto max-w-4xl px-6 py-12">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-orange-400 mb-6">Create New Post</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="max-w-md mx-auto space-y-6">
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="title" id="floating_title" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-orange-500 focus:outline-none focus:ring-0 focus:border-orange-600 peer" placeholder=" " required />
                <label for="floating_title" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Title</label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <textarea name="content" id="floating_content" rows="4" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-orange-500 focus:outline-none focus:ring-0 focus:border-orange-600 peer" placeholder=" " required></textarea>
                <label for="floating_content" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Content</label>
            </div>

        <div class="relative z-0 w-full mb-5 group">
            <select name="category" id="floating_category" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-gray-800 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-orange-500 focus:outline-none focus:ring-0 focus:border-orange-600 peer" required>
                <?php while ($category = $categories_result->fetch_assoc()): ?>
                    <option value="<?php echo $category['category_id']; ?>" class="bg-gray-700 hover:bg-gray-600">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="floating_category" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-7 scale-75 top-3 left-0 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Category</label>
        </div>

            <div class="flex items-center space-x-3">
                <input type="checkbox" name="is_question" id="is_question" value="1" class="h-5 w-5 text-orange-400 bg-gray-700 rounded border-gray-600 focus:ring-orange-400">
                <label for="is_question" class="text-lg">Is this a question?</label>
            </div>

            <!-- Image Upload Section -->
            <div class="relative z-0 w-full mb-5 group">
                <label for="image" class="block text-sm text-gray-500 mb-2">Upload Image</label>
                <div class="flex items-center justify-between">
                    <!-- File Upload Button -->
                    <label for="image" class="bg-orange-500 text-white px-6 py-2 rounded-lg cursor-pointer hover:bg-orange-400 flex items-center space-x-2">
                        <span>Choose File</span>
                    </label>

                    <!-- File Input -->
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="updateFileName()" />

                    <!-- File Name Display -->
                    <span id="file-name" class="text-sm text-gray-500 ml-4">No file chosen</span>
                </div>
            </div>

            <button type="submit" name="submit_post" class="w-full py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400">Post</button>
        </form>
    </div>
</main>
</body>
</html>
<script>
    function updateFileName() {
        const fileInput = document.getElementById('image');
        const fileNameDisplay = document.getElementById('file-name');
        
        // Display the selected file name
        if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = fileInput.files[0].name;
        } else {
            fileNameDisplay.textContent = 'No file chosen';
        }
    }
</script>