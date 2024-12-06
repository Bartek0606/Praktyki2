<?php
ob_start();
session_start();

include '../../../db_connection.php';
include '../../Component/navbar.php';

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Check if item_id is provided in the URL
if (!isset($_GET['item_id']) || !is_numeric($_GET['item_id'])) {
    echo "Invalid item ID.";
    exit;
}

$itemId = $_GET['item_id'];

// Fetch the item details, including owner information
$sql_item = "SELECT i.item_id, i.name, i.image, i.description, i.price, i.created_at, c.name AS category_name, u.user_id, u.username
             FROM items i
             LEFT JOIN categories c ON i.category_id = c.category_id
             LEFT JOIN users u ON i.user_id = u.user_id
             WHERE i.item_id = ?";
$stmt = $conn->prepare($sql_item);
$stmt->bind_param("i", $itemId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Item not found.";
    exit;
}

$item = $result->fetch_assoc();
$formatted_date = date("F j, Y, g:i a", strtotime($item['created_at']));
$image_data = base64_encode($item['image']);
$image_html = $item['image'] ? "<img src='data:image/jpeg;base64,$image_data' alt='" . htmlspecialchars($item['name']) . "' class='item-image' />" : '';

// Fetch related items
$sql_related_items = "SELECT i.item_id, i.name, i.image, i.price 
                      FROM items i
                      WHERE i.category_id = ? AND i.item_id != ? LIMIT 4";
$stmt_related = $conn->prepare($sql_related_items);
$stmt_related->bind_param("ii", $item['category_id'], $itemId);
$stmt_related->execute();
$related_items_result = $stmt_related->get_result();

// Fetch other items from the same user
$sql_user_items = "SELECT i.item_id, i.name, i.image, i.price 
                   FROM items i
                   WHERE i.user_id = ? AND i.item_id != ? LIMIT 4";
$stmt_user_items = $conn->prepare($sql_user_items);
$stmt_user_items->bind_param("ii", $item['user_id'], $itemId);
$stmt_user_items->execute();
$user_items_result = $stmt_user_items->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../glowna.css">
    <link rel="stylesheet" href="../../../navbar.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../../item_details.css">
    <link rel="stylesheet" href="../../../items.css">
    <title><?php echo htmlspecialchars($item['name']); ?> - Item Details</title>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container">
    <!-- Back Button -->
    <!-- <div class="back-button">
        <a href="items.php" class="btn-back">← Back to Items</a>
    </div> -->

    <div class="item-details">
        <div class="item-image">
            <?php echo $image_html; ?>
        </div>
        <div class="item-info">
            <h1><?php echo htmlspecialchars($item['name']); ?></h1>
            <p><strong>Owner:</strong> 
                <a href="user.php?id=<?php echo htmlspecialchars($item['user_id']); ?>" class="user-profile-link">
                    <?php echo htmlspecialchars($item['username']); ?>
                </a>
            </p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category_name'] ?? 'Uncategorized'); ?></p>
            <p><strong>Price:</strong> <?php echo htmlspecialchars($item['price']); ?> zł</p>
            <p><strong>Posted on:</strong> <?php echo $formatted_date; ?></p>

            <!-- Message Button -->
            <?php if ($isLoggedIn && $userId != $item['user_id']): ?>
                <form action="message.php?id=<?php echo $item['user_id']; ?>" method="POST">
                    <button type="submit" class="message-btn">Message the Seller</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    

    <!-- Related Items Section -->
    <?php if ($related_items_result->num_rows > 0): ?>
        <div class="related-items">
            <h3>Related Items</h3>
            <div class="related-items-list">
                <?php while ($related = $related_items_result->fetch_assoc()): ?>
                    <?php
                    $related_image_data = base64_encode($related['image']);
                    $related_image_html = $related['image'] ? "<img src='data:image/jpeg;base64,$related_image_data' alt='" . htmlspecialchars($related['name']) . "' class='related-item-image' />" : '';
                    ?>
                    <div class="related-item-card">
                        <a href="item_details.php?item_id=<?php echo htmlspecialchars($related['item_id']); ?>">
                            <?php echo $related_image_html; ?>
                            <h4><?php echo htmlspecialchars($related['name']); ?></h4>
                            <p><strong>Price:</strong> <?php echo htmlspecialchars($related['price']); ?> zł</p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- User's Other Items Section -->
    <?php if ($user_items_result->num_rows > 0): ?>
        <div class="user-items">
            <h3>Other Items by <?php echo htmlspecialchars($item['username']); ?></h3>
            <div class="user-items-list">
                <?php while ($user_item = $user_items_result->fetch_assoc()): ?>
                    <?php
                    $user_item_image_data = base64_encode($user_item['image']);
                    $user_item_image_html = $user_item['image'] ? "<img src='data:image/jpeg;base64,$user_item_image_data' alt='" . htmlspecialchars($user_item['name']) . "' class='user-item-image' />" : '';
                    ?>
                    <div class="user-item-card">
                        <a href="item_details.php?item_id=<?php echo htmlspecialchars($user_item['item_id']); ?>">
                            <?php echo $user_item_image_html; ?>
                            <h4><?php echo htmlspecialchars($user_item['name']); ?></h4>
                            <p><strong>Price:</strong> <?php echo htmlspecialchars($user_item['price']); ?> zł</p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>
</main>
</body>
</html>

<?php
$stmt->close();
$stmt_related->close();
$stmt_user_items->close();
$conn->close();
ob_end_flush();
?>
