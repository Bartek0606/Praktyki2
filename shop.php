<?php
ob_start();
session_start();

include 'db_connection.php';
include 'Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Fetch categories for the category filter
$sql_categories = "SELECT category_id, name FROM categories";
$categories_result = $conn->query($sql_categories);

// Default category filter
$category_filter = isset($_GET['category']) ? (int)$conn->real_escape_string($_GET['category']) : 0;

// Fetch items from the database with category filter
$sort = isset($_GET['sort']) ? $conn->real_escape_string($_GET['sort']) : 'newest';
$order_by = 'created_at DESC';

if ($sort === 'oldest') {
    $order_by = 'created_at ASC';
} elseif ($sort === 'price') {
    $order_by = 'price ASC';
} elseif ($sort === 'price_desc') {
    $order_by = 'price DESC';
}

$category_condition = $category_filter > 0 ? "WHERE i.category_id = $category_filter" : "";
$sql_items = "SELECT i.item_id, i.name, i.image, i.description, i.price, i.created_at, c.name AS category_name 
              FROM items i
              LEFT JOIN categories c ON i.category_id = c.category_id
              $category_condition
              ORDER BY $order_by";

$items_result = $conn->query($sql_items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="shop.css">
    <title>Items for Sale</title>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container">
    <div class="nagl">
        <h2>Items for Sale</h2>
        <hr class="divider">
    </div>

    <div class="filter-menu">
        <form method="GET" action="">
            <label for="category">Category:</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="0" <?php echo $category_filter === 0 ? 'selected' : ''; ?>>All Categories</option>
                <?php
                if ($categories_result->num_rows > 0) {
                    while ($category = $categories_result->fetch_assoc()) {
                        $selected = $category['category_id'] === $category_filter ? 'selected' : '';
                        echo "<option value='" . $category['category_id'] . "' $selected>" . htmlspecialchars($category['name']) . "</option>";
                    }
                }
                ?>
            </select>

            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Date: Newest</option>
                <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Date: Oldest</option>
                <option value="price" <?php echo $sort === 'price' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
            </select>
        </form>
    </div>

    <section class="items-list">
        <?php
        if ($items_result->num_rows > 0) {
            while ($item = $items_result->fetch_assoc()) {
                $formatted_date = date("F j, Y, g:i a", strtotime($item['created_at']));
                $image_data = base64_encode($item['image']);
                $image_html = $item['image'] ? "<img src='data:image/jpeg;base64,$image_data' alt='" . htmlspecialchars($item['name']) . "' />" : '';

                echo "<div class='item-card'>";
                echo $image_html;
                echo "<h3>" . htmlspecialchars($item['name']) . "</h3>";
                echo "<p><strong>Description:</strong> " . htmlspecialchars($item['description']) . "</p>";
                echo "<p><strong>Category:</strong> " . htmlspecialchars($item['category_name'] ?? 'Uncategorized') . "</p>";
                echo "<p><strong>Price:</strong> " . htmlspecialchars($item['price']) . " zł</p>";
                echo "<p><strong>Posted on:</strong> " . $formatted_date . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No items available.</p>";
        }
        ?>
    </section>
</main>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
