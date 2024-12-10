<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Items for Sale • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto max-w-6xl px-6 py-12">
    <div class="mb-8">
        <h2 class="text-4xl font-bold text-orange-400 mb-4">Items for Sale</h2>
        <hr class="border-gray-600">
    </div>

    <!-- Filters Section -->
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg mb-8">
        <form method="GET" action="" class="space-y-4">
            <!-- Categories Filter -->
            <div>
                <label class="block text-lg font-semibold mb-2">Categories:</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <?php if ($categories_result->num_rows > 0): ?>
                        <?php while ($category = $categories_result->fetch_assoc()): ?>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="categories[]" value="<?php echo $category['category_id']; ?>"
                                       class="h-5 w-5 text-orange-400 bg-gray-700 rounded border-gray-600"
                                    <?php echo in_array($category['category_id'], $selected_categories) ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($category['name']); ?></span>
                            </label>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sort Filter -->
            <div>
                <label class="block text-lg font-semibold mb-2">Sort by:</label>
                <select name="sort" id="sort"
                        class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400">
                    <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Date: Newest</option>
                    <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Date: Oldest</option>
                    <option value="price" <?php echo $sort === 'price' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-lg focus:outline-none">
                Apply Filters
            </button>
        </form>
    </div>

    <!-- Items List -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if ($items_result->num_rows > 0): ?>
            <?php while ($item = $items_result->fetch_assoc()): ?>
                <?php
                $formatted_date = date("F j, Y, g:i a", strtotime($item['created_at']));
                $image_data = base64_encode($item['image']);
                $image_html = $item['image'] ? "<img src='data:image/jpeg;base64,$image_data' alt='" . htmlspecialchars($item['name']) . "' class='rounded-lg shadow-md mb-4'>" : '';
                ?>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <a href="item_details.php?item_id=<?php echo htmlspecialchars($item['item_id']); ?>">
                        <?php echo $image_html; ?>
                        <h3 class="text-xl font-bold text-orange-400 mb-2"><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="text-sm text-gray-400 mb-1"><strong>Owner:</strong>
                            <a class="font-bold " href="user.php?id=<?php echo htmlspecialchars($item['user_id']); ?>"
                               class="text-blue-400 hover:underline">
                                <?php echo htmlspecialchars($item['username']); ?>
                            </a>
                        </p>
                        <p class="text-sm text-gray-400 mb-1"><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
                        <p class="text-sm text-gray-400 mb-1"><strong>Category:</strong> <?php echo htmlspecialchars($item['category_name'] ?? 'Uncategorized'); ?></p>
                        <p class="text-sm text-gray-400 mb-1"><strong>Price:</strong> <?php echo htmlspecialchars($item['price']); ?> zł</p>
                        <p class="text-sm text-gray-400"><strong>Posted on:</strong> <?php echo $formatted_date; ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-gray-400 text-center col-span-full">No items available.</p>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
