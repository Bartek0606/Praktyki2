<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Item</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto max-w-4xl px-6 py-12">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-orange-400 mb-6">Edit Item</h1>

        <form action="" method="POST">
            <div class="mb-6">
                <label for="name" class="block text-orange-400">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                       class="w-full p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <div class="mb-6">
                <label for="description" class="block text-orange-400">Description</label>
                <textarea id="description" name="description" 
                          class="w-full p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"><?php echo htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="mb-6">
                <label for="category_id" class="block text-orange-400">Category</label>
                <select id="category_id" name="category_id" 
                        class="w-full p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <?php while ($category = $categoriesResult->fetch_assoc()): ?>
                        <option value="<?php echo $category['category_id']; ?>" 
                                <?php echo $item['category_id'] == $category['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-6">
                <label for="price" class="block text-orange-400">Price</label>
                <input type="number" id="price" name="price" step="0.01" 
                       value="<?php echo htmlspecialchars($item['price'], ENT_QUOTES, 'UTF-8'); ?>" 
                       class="w-full p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <button type="submit" 
                    class="w-full py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400 transition ease-in-out duration-150">
                Update Item
            </button>

            <?php if (isset($error)): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mt-4">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
        </form>
    </div>
</main>
</body>
</html>
