<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Post • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto max-w-4xl px-6 py-12">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-orange-400 mb-6">Edit Post</h2>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="space-y-2">
                <label for="title" class="block text-lg font-semibold">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>" 
                       class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required>
            </div>

            <div class="space-y-2">
                <label for="content" class="block text-lg font-semibold">Content:</label>
                <textarea name="content" id="content" rows="6" 
                          class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required><?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="space-y-2">
                <label for="category" class="block text-lg font-semibold">Category:</label>
                <select name="category" id="category" class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required>
                    <?php while ($category = $categoriesResult->fetch_assoc()): ?>
                        <option value="<?php echo $category['category_id']; ?>" 
                                <?php echo $post['category_id'] == $category['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- <div class="space-y-2">
                <label for="image" class="block text-lg font-semibold">Upload New Image:</label>
                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-gray-400">
            </div> -->

            <button type="submit" name="edit_post" 
                    class="w-full py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400">
                Save Changes
            </button>

            <?php if (isset($error)): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
        </form>
    </div>
</main>
</body>
</html>
