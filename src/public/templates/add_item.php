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

if (!$isLoggedIn) {
    header("Location: login.php"); // Przekieruj na stronę logowania, jeśli użytkownik nie jest zalogowany
    exit;
}

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$message = null;
$messageClass = "";

// Obsługa formularza dodawania przedmiotu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category'];

    // Obsługa przesłanego obrazu
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
        $image = $conn->real_escape_string($image);

        // Wstaw nowy przedmiot do bazy danych
        $sql_add_item = "
            INSERT INTO items (name, description, price, category_id, image, created_at, user_id)
            VALUES ('$item_name', '$description', $price, $category_id, '$image', NOW(), $userId)
        ";

        if ($conn->query($sql_add_item) === TRUE) {
            $message = "Item added successfully!";
            $messageClass = "success";
            header("Location: items.php");
        } else {
            $message = "Error adding item: " . $conn->error;
            $messageClass = "error";
        }
    } else {
        $message = "Error uploading image. Please try again.";
        $messageClass = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Add Item • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto max-w-4xl px-6 py-12">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-orange-400 mb-6">Add a New Item</h2>

        <?php if ($message): ?>
            <div class="bg-<?php echo $messageClass === 'success' ? 'green' : 'red'; ?>-600 text-white p-4 rounded-lg mb-6">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
            <div class="space-y-2">
                <label for="item_name" class="block text-lg font-semibold">Item Name:</label>
                <input type="text" name="item_name" id="item_name" class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required>
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-lg font-semibold">Description:</label>
                <textarea name="description" id="description" rows="4" class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required></textarea>
            </div>

            <div class="space-y-2">
                <label for="price" class="block text-lg font-semibold">Price (zł):</label>
                <input type="number" name="price" id="price" min="0" step="0.01" class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required>
            </div>

            <div class="space-y-2">
                <label for="category" class="block text-lg font-semibold">Category:</label>
                <select name="category" id="category" class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required>
                    <option value="">Select Category</option>
                    <?php
                    $sql_categories = "SELECT category_id, name FROM categories";
                    $categories_result = $conn->query($sql_categories);
                    if ($categories_result->num_rows > 0) {
                        while ($category = $categories_result->fetch_assoc()) {
                            echo "<option value='" . $category['category_id'] . "'>" . htmlspecialchars($category['name']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="space-y-2">
                <label for="image" class="block text-lg font-semibold">Upload Image:</label>
                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-gray-400">
            </div>

            <button type="submit" name="add_item" class="w-full py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400">Add Item</button>
        </form>
    </div>
</main>
</body>
</html>
<?php
$conn->close();
ob_end_flush();
?>

