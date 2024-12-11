<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<main class="dashboard p-8 bg-gray-50 ml-64 min-h-screen" style="padding-top: 6rem;">
    <h2 class="text-xl font-semibold text-center text-gray-800 mt-3 mb-4">All Posts</h2>

    <div class="posts-container m-auto space-y-4 w-4/6 mt-3">
        <?php
        // Pobieranie wszystkich postów
        $posts = $postManager->getAllPosts();

        if (empty($posts)): ?>
            <p class="text-gray-500 italic text-center">No posts found.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post bg-white shadow-md rounded-lg p-6">
                    <div class="post-image w-1/6 mr-5 h-full rounded-md overflow-hidden">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($post["image"]); ?>" alt="Post Image" class="w-full h-full object-cover">
                        </div>
                    <div class="post-header flex justify-between items-center mb-3">
                        <p class="font-bold text-gray-800">Title: <?php echo htmlspecialchars($post['title']); ?></p>
                        <p class="text-gray-500 text-sm">Created at: <?php echo date('Y-m-d H:i:s', strtotime($post['created_at'])); ?></p>
                    </div>
                    <div class="post-body mb-4">
                        <p class="text-gray-600">Category: <?php echo htmlspecialchars($post['category_name']); ?></p>
                        <p class="text-gray-600 mt-2">Content: <?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    </div>

                    <div class="post-footer flex justify-end space-x-2">
                    <button 
                        class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition editpost_button"
                        data-post-id="<?php echo $post['post_id']; ?>"
                        data-post-title="<?php echo htmlspecialchars($post['title']); ?>"
                        data-post-content="<?php echo nl2br(htmlspecialchars($post['content'])); ?>"
                        data-post-category="<?php echo htmlspecialchars($post['category_name']); ?>">
                        Edit
                    </button>
                    <form method="POST" action="logic/categories_logic.php" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        <input type="hidden" name="delete_post_id" value="<?php echo $post['post_id']; ?>">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            Delete
                        </button>
                    </form>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

 <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>

    <!-- Popup do edycji -->
    <div id="popupModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Post</h2>
            <form method="POST" action="logic/posts_logic.php" class="space-y-4">
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
                    <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400"> Cancel </button>
                    <button type="submit" name="edit_post" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"> Save Changes </button>
                </div>
            </form>

        </div>
    </div>

</body>
</html>
