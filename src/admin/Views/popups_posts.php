<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>

    <!-- Popup do edycji -->
    <div id="popupModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Post</h2>

            <form method="POST" action="" class="space-y-4">
                <input type="hidden" id="editPostId" name="post_id" value="">
                <div>
                    <label for="editTitle" class="block text-gray-700 font-medium">Title:</label>
                    <input type="text" id="editTitle" name="editTitle" value="" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="editContent" class="block text-gray-700 font-medium">Content:</label>
                    <textarea
                        id="editContent"
                        name="editContent"
                        required
                        class="w-full h-32 px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none"></textarea>
                </div>
                <div>
                    <label for="category_id" class="block text-gray-700 font-medium">Category:</label>
                    <select
                        id="category_id"
                        name="category_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none"
                    >
                        <?php
                        $categoriesQuery = $conn->query("SELECT category_id, name FROM categories");
                        while ($category = $categoriesQuery->fetch_assoc()) {
                            echo "<option value=\"{$category['category_id']}\">" . htmlspecialchars($category['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <button
                        type="button"
                        id="cancelEdit"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400"
                    > Cancel
                    </button>
                    <button
                        type="submit"
                        name="saveChanges"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    > Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>