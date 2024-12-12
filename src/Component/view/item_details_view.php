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
<?php if (isset($_SESSION['purchase_success']) && $_SESSION['purchase_success']): ?>
        <div class="bg-green-500 text-white text-center py-3 rounded-lg mb-6">
            Purchase successful! Thank you for your order.
        </div>
        <?php unset($_SESSION['purchase_success']); ?>
    <?php endif; ?>
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-orange-400 mb-6"><?php echo htmlspecialchars($item['name']); ?></h1>

        <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-8">
            <!-- Sekcja obrazka -->
            <div class="flex-shrink-0 w-full md:w-1/2">
                <?php
                if ($item['image']) {
                    $imageData = base64_encode($item['image']);
                    echo "<img src='data:image/jpeg;base64,$imageData' alt='" . htmlspecialchars($item['name']) . "' class='rounded-lg w-full' />";
                } else {
                    echo '<div class="bg-gray-700 h-64 flex items-center justify-center text-gray-400">No Image Available</div>';
                }
                ?>
            </div>

            <!-- Sekcja szczegółów -->
            <div class="flex-grow">
                <p><strong class="text-orange-400">Owner:</strong> 
                    <a class="text-white font-bold" href="user.php?id=<?php echo htmlspecialchars($item['user_id']); ?>" class="text-blue-400 hover:underline">
                        <?php echo htmlspecialchars($item['username']); ?>
                    </a>
                </p>
                <p><strong class="text-orange-400">Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
                <p><strong class="text-orange-400">Category:</strong> <?php echo htmlspecialchars($item['category_name'] ?? 'Uncategorized'); ?></p>
                <p><strong class="text-orange-400">Price:</strong> <?php echo htmlspecialchars($item['price']); ?> zł</p>
                <p><strong class="text-orange-400">Posted on:</strong> <?php echo date("F j, Y, g:i a", strtotime($item['created_at'])); ?></p>

                <!-- Purchase, Message, and Edit Buttons -->
<div class="mt-6 flex flex-col space-y-4">
    <!-- Message Button -->
    <?php if ($isLoggedIn && $userId != $item['user_id']): ?>
        <a href="message.php?id=<?php echo htmlspecialchars($item['user_id']); ?>" 
           class="py-2 px-6 bg-blue-500 text-white font-bold rounded-lg text-center hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 transition ease-in-out duration-150">
           Message Seller
        </a>
    <?php endif; ?>

    <!-- Purchase Button -->
    <?php if ($isLoggedIn && $userId != $item['user_id'] && !$item['purchased']): ?>
        <form action="purchase.php" method="POST">
            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['item_id']); ?>">
            <button type="submit" name="purchase" class="w-full py-2 px-6 bg-green-500 text-white font-bold rounded-lg text-center hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-green-400 transition ease-in-out duration-150">
                Buy Now
            </button>
        </form>
    <?php elseif ($item['purchased']): ?>
        <div class="w-full py-2 px-6 bg-gray-600 text-white-400 rounded-lg text-center">
            Item already purchased
        </div>
    <?php endif; ?>

    <!-- Edit Button (only if the item is owned by the logged-in user) -->
    <?php if ($isLoggedIn && $userId == $item['user_id']): ?>
        <a href="edit_item.php?item_id=<?php echo htmlspecialchars($item['item_id']); ?>" 
           class="py-2 px-6 bg-yellow-500 text-white font-bold rounded-lg text-center hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition ease-in-out duration-150">
           Edit Item
        </a>
    <?php endif; ?>
</div>

            </div>
        </div>
    </div>

    <!-- Sekcja "Other items from this user" -->
    <?php if ($userItems && $userItems->num_rows > 0): ?>
        <div class="mt-12 bg-gray-800 p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-orange-400 mb-4">Other items from <?php echo htmlspecialchars($item['username']); ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php while ($userItem = $userItems->fetch_assoc()): ?>
                    <a href="item_details.php?item_id=<?php echo $userItem['item_id']; ?>" class="block bg-gray-700 rounded-lg p-4 shadow hover:shadow-lg hover:bg-gray-600 transition">
                        <?php
                        if ($userItem['image']) {
                            $userItemImage = base64_encode($userItem['image']);
                            echo "<img src='data:image/jpeg;base64,$userItemImage' alt='" . htmlspecialchars($userItem['name']) . "' class='rounded-lg w-full h-32 object-cover mb-4' />";
                        } else {
                            echo '<div class="bg-gray-600 h-32 flex items-center justify-center text-gray-400 mb-4">No Image</div>';
                        }
                        ?>
                        <h3 class="text-lg font-semibold text-orange-400 truncate"><?php echo htmlspecialchars($userItem['name']); ?></h3>
                        <p class="text-gray-300"><?php echo htmlspecialchars($userItem['price']); ?> zł</p>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
