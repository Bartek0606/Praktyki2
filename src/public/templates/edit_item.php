<?php
ob_start();
session_start();

include '../../../db_connection.php'; // Adjust this path as needed
include '../../Component/navbar.php'; // Adjust this path as needed

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$itemId = $_GET['item_id'];

$sql = "SELECT * FROM items WHERE item_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $itemId, $_SESSION['user_id']);
$stmt->execute();
$item_result = $stmt->get_result();

if ($item_result->num_rows === 0) {
    echo "Item not found or you do not have permission to edit this item.";
    exit;
}

$item = $item_result->fetch_assoc();

// Fetch categories for the dropdown
$category_sql = "SELECT category_id, name FROM categories";
$category_result = $conn->query($category_sql);

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;
$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];

    // Update the item details in the database
    $update_sql = "UPDATE items SET name = ?, description = ?, category_id = ?, price = ? WHERE item_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssiii", $name, $description, $category_id, $price, $itemId);
    $update_stmt->execute();

    // Redirect to the updated item details page with a success message
    header("Location: item_details.php?item_id=$itemId&success=true");
    exit;
}
?>

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
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" class="w-full p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <div class="mb-6">
                <label for="description" class="block text-orange-400">Description</label>
                <textarea id="description" name="description" class="w-full p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"><?php echo htmlspecialchars($item['description']); ?></textarea>
            </div>

            <div class="mb-6">
                <label for="category_id" class="block text-orange-400">Category</label>
                <select id="category_id" name="category_id" class="w-full p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <?php while ($category = $category_result->fetch_assoc()): ?>
                        <option value="<?php echo $category['category_id']; ?>" <?php echo $item['category_id'] == $category['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-6">
                <label for="price" class="block text-orange-400">Price</label>
                <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" class="w-full p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <button type="submit" class="w-full py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400 transition ease-in-out duration-150">
                Update Item
            </button>
        </form>
    </div>
</main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
ob_end_flush();
?>
