<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo htmlspecialchars($item['name']); ?> - Item Details</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto max-w-4xl px-6 py-12">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        <!-- Success Message Purchase-->
        <?php if (isset($_SESSION['purchase_success']) && $_SESSION['purchase_success']): ?>
            <div class="mb-6 py-3 px-4 bg-green-500 text-white rounded-lg">
                Purchase successful!
            </div>
            <?php unset($_SESSION['purchase_success']); // Clear the success flag ?>
        <?php endif; ?>

        <!-- Success Message Update-->
        <?php if (isset($_SESSION['update_success']) && $_SESSION['update_success']): ?>
            <div class="mb-6 py-3 px-4 bg-green-500 text-white rounded-lg">
                Item updated successfully!
            </div>
            <?php unset($_SESSION['update_success']); // Clear the success flag ?>
        <?php endif; ?>

        <!-- Success Message Add-->
        <?php if (isset($_SESSION['add_item_success']) && $_SESSION['add_item_success']): ?>
            <div class="mb-6 py-3 px-4 bg-green-500 text-white rounded-lg">
                Item added successfully!
            </div>
            <?php unset($_SESSION['add_item_success']); // Clear the success flag ?>
        <?php endif; ?>

        <h1 class="text-3xl font-bold text-orange-400 mb-6"><?php echo htmlspecialchars($item['name']); ?></h1>

        <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-8">
            <!-- Image Section -->
            <div class="flex-shrink-0 w-full md:w-1/2">
                <?php echo $image_html ?: '<div class="bg-gray-700 h-64 flex items-center justify-center text-gray-400">No Image Available</div>'; ?>
            </div>

            <!-- Info Section -->
            <div class="flex-grow">
                <p><strong class="text-orange-400">Owner:</strong> 
                    <a class="text-white font-bold" href="user.php?id=<?php echo htmlspecialchars($item['user_id']); ?>" class="text-blue-400 hover:underline">
                        <?php echo htmlspecialchars($item['username']); ?>
                    </a>
                </p>
                <p><strong class="text-orange-400">Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
                <p><strong class="text-orange-400">Category:</strong> <?php echo htmlspecialchars($item['category_name'] ?? 'Uncategorized'); ?></p>
                <p><strong class="text-orange-400">Price:</strong> <?php echo htmlspecialchars($item['price']); ?> zł</p>
                <p><strong class="text-orange-400">Posted on:</strong> <?php echo $formatted_date; ?></p>

                <!-- Edit Button (only if the item is owned by the logged-in user) -->
                <?php if ($isLoggedIn && $userId == $item['user_id']): ?>
                    <div class="mt-6">
                        <a href="edit_item.php?item_id=<?php echo htmlspecialchars($item['item_id']); ?>" class="w-full py-3 px-6 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 transition ease-in-out duration-150">
                            Edit Item
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Message Button -->
                <?php if ($isLoggedIn && $userId != $item['user_id']): ?>
                    <form action="message.php?id=<?php echo $item['user_id']; ?>" method="POST" class="mt-6">
                        <button type="submit" class="w-full py-3 px-6 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400 transition ease-in-out duration-150">Message the Seller</button>
                    </form>
                <?php endif; ?>

                <!-- Purchase Button -->
                <?php if ($isLoggedIn && $userId != $item['user_id'] && !$item['purchased']): ?>
                    <form action="purchase.php" method="POST" class="mt-6">
                        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['item_id']); ?>">
                        <button type="submit" name="purchase" class="w-full py-3 px-6 bg-green-500 text-white font-bold rounded-lg hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-green-400 transition ease-in-out duration-150">Buy Now</button>
                    </form>
                <?php elseif ($item['purchased']): ?>
                    <div class="mt-6 py-3 px-6 bg-gray-600 text-white-400 rounded-lg text-center">
                        Item already purchased
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- User's Other Items Section -->
        <?php if ($user_items_result->num_rows > 0): ?>
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-orange-400 mb-6">Other Items by <?php echo htmlspecialchars($item['username']); ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php while ($user_item = $user_items_result->fetch_assoc()): ?>
                        <div class="bg-gray-700 p-4 rounded-lg shadow-lg">
                            <a href="item_details.php?item_id=<?php echo htmlspecialchars($user_item['item_id']); ?>" class="block">
                                <?php echo $user_item['image'] 
                                    ? "<img src='data:image/jpeg;base64," . base64_encode($user_item['image']) . "' alt='" . htmlspecialchars($user_item['name']) . "' class='rounded-t-lg h-48 w-full object-cover' />" 
                                    : '<div class="bg-gray-600 h-48 flex items-center justify-center text-gray-400">No Image</div>'; ?>
                                <h4 class="text-lg font-bold text-white mt-4"><?php echo htmlspecialchars($user_item['name']); ?></h4>
                                <p class="text-orange-400"><strong>Price:</strong> <?php echo htmlspecialchars($user_item['price']); ?> zł</p>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-orange-400 mb-6">Other Items by <?php echo htmlspecialchars($item['username']); ?></h3>
                <p class="text-gray-400">No other items available by <?php echo htmlspecialchars($item['username']); ?>.</p>
            </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
