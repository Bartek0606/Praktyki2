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

// Obsługa sortowania
$sort = isset($_GET['sort']) ? $conn->real_escape_string($_GET['sort']) : 'newest';
$order_by = 'created_at DESC';

if ($sort === 'oldest') {
    $order_by = 'created_at ASC';
} elseif ($sort === 'price') {
    $order_by = 'price ASC';
} elseif ($sort === 'price_desc') {
    $order_by = 'price DESC';
}

// Obsługa filtrowania kategorii
$selected_categories = isset($_GET['categories']) ? array_map('intval', $_GET['categories']) : [];
if (!empty($selected_categories)) {
    $category_condition = "i.category_id IN (" . implode(',', $selected_categories) . ")";
} else {
    $category_condition = "1=1"; // Domyślnie brak filtra kategorii
}

// Pobieranie przedmiotów
$sql_items = "SELECT i.item_id, i.name, i.image, i.description, i.price, i.created_at, c.name AS category_name, u.user_id, u.username 
              FROM items i
              LEFT JOIN categories c ON i.category_id = c.category_id
              LEFT JOIN users u ON i.user_id = u.user_id
              WHERE $category_condition
              ORDER BY $order_by";
$items_result = $conn->query($sql_items);

// Pobieranie kategorii
$sql_categories = "SELECT category_id, name FROM categories";
$categories_result = $conn->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../glowna.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../../navbar.css">
    <link rel="stylesheet" href="../../../items.css">
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

    <?php if ($isLoggedIn): ?>
        <div class="add-item-button">
            <a href="add_item.php">Add New Item</a>
        </div>
    <?php endif; ?>

    <div class="filter-menu">
    <form method="GET" action="">
        <div class="categories-filter">
            <label for="categories"><strong>Categories:</strong></label>
            <div class="checkbox-group">
                <?php
                if ($categories_result->num_rows > 0) {
                    while ($category = $categories_result->fetch_assoc()) {
                        $checked = in_array($category['category_id'], $selected_categories) ? 'checked' : '';
                        echo "<label>";
                        echo "<input type='checkbox' name='categories[]' value='" . $category['category_id'] . "'>";
                        echo htmlspecialchars($category['name']);
                        echo "</label>";
                    }
                }
                ?>
            </div>
        </div>
        
        <div class="sort-filter">
            <label for="sort"><strong>Sort by:</strong></label>
            <select name="sort" id="sort">
                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Date: Newest</option>
                <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Date: Oldest</option>
                <option value="price" <?php echo $sort === 'price' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
            </select>
        </div>

        <button type="submit" class="apply-filters-btn">Apply Filters</button>
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
            echo "<a href='item_details.php?item_id=" . htmlspecialchars($item['item_id']) . "'>";
            echo $image_html;
            echo "<h3>" . htmlspecialchars($item['name']) . "</h3>";
            echo "<p><strong>Owner:</strong> <a href='user.php?id=" . htmlspecialchars($item['user_id']) . "' class='user-profile-link'>" . htmlspecialchars($item['username']) . "</a></p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($item['description']) . "</p>";
            echo "<p><strong>Category:</strong> " . htmlspecialchars($item['category_name'] ?? 'Uncategorized') . "</p>";
            echo "<p><strong>Price:</strong> " . htmlspecialchars($item['price']) . " zł</p>";
            echo "<p><strong>Posted on:</strong> " . $formatted_date . "</p>";
            echo "</a>";
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
