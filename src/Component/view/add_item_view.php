<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../public/js/add_item.js"></script>
    <title>Add Item • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto max-w-4xl px-6 py-12">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-orange-400 mb-6">Add a New Item</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="../../function/add_item_function.php" enctype="multipart/form-data" class="max-w-md mx-auto space-y-6" onsubmit="return validateForm()">
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="item_name" id="floating_item_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-orange-500 focus:outline-none focus:ring-0 focus:border-orange-600 peer" placeholder=" " required />
                <label for="floating_item_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Item Name</label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <textarea name="description" id="floating_description" rows="4" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-orange-500 focus:outline-none focus:ring-0 focus:border-orange-600 peer" placeholder=" " required></textarea>
                <label for="floating_description" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Description</label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input type="number" name="price" id="floating_price" min="0" step="0.01" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-orange-500 focus:outline-none focus:ring-0 focus:border-orange-600 peer" placeholder=" " required />
                <label for="floating_price" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Price (zł)</label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <select name="category" id="floating_category" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-gray-800 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-orange-500 focus:outline-none focus:ring-0 focus:border-orange-600 peer" required>
                    <option value="">Select Category</option>
                    <?php
                    $sql_categories = "SELECT category_id, name FROM categories";
                    $categories_result = $conn->query($sql_categories);
                    if ($categories_result->num_rows > 0) {
                        while ($category = $categories_result->fetch_assoc()) {
                            echo "<option value='" . $category['category_id'] . "'>" . htmlspecialchars($category['name']) . "</option>";
                        }
                    }
                    ?>
                </select>
                <label for="floating_category" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-7 scale-75 top-3 left-0 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Category</label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <label for="image" class="block text-sm text-gray-500 mb-2">Upload Image</label>
                <div class="flex items-center justify-between">
                    <label for="image" class="bg-orange-500 text-white px-6 py-2 rounded-lg cursor-pointer hover:bg-orange-400 flex items-center space-x-2">
                        <span>Choose File</span>
                    </label>
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="updateFileName()" />
                    <span id="file-name" class="text-sm text-gray-500 ml-4">No file chosen</span>
                </div>
            </div>

            <button type="submit" name="add_item" class="w-full py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400">Add Item</button>
        </form>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg text-white max-w-xs w-full">
            <h3 class="text-lg font-bold mb-2">Error</h3>
            <p>Please upload an image before submitting.</p>
            <button onclick="document.getElementById('errorModal').classList.add('hidden')" class="mt-4 py-1 px-3 bg-orange-500 text-white rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400">Close</button>
        </div>
    </div>
</main>
<script>
    function updateFileName() {
        const fileInput = document.getElementById('image');
        const fileNameDisplay = document.getElementById('file-name');
        
        if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = fileInput.files[0].name;
        } else {
            fileNameDisplay.textContent = 'No file chosen';
        }
    }
</script>
</body>
</html>
