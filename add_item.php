<?php
ob_start();
session_start();
include 'db_connection.php';
include 'Component/navbar.php';

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
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="add_item.css">
    <title>Add Item</title>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container">
    <h2>Add a New Item</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="item_name">Item Name:</label>
        <input type="text" name="item_name" id="item_name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required></textarea>

        <label for="price">Price (zł):</label>
        <input type="number" name="price" id="price" min="0" step="0.01" required>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
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

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <button type="submit" name="add_item">Add Item</button>
    </form>

    <?php if ($message): ?>
        <div class="message <?php echo $messageClass; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
<?php
$conn->close();
ob_end_flush();
?>
