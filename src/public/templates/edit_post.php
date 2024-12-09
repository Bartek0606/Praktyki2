<?php
ob_start();
session_start();
include '../../../db_connection.php';
include '../../Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Pobranie ID posta
$postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
if ($postId <= 0) {
    echo "<p>Invalid post ID.</p>";
    exit;
}

// Pobranie szczegółów posta
$sqlPostDetails = "SELECT * FROM posts WHERE post_id = ?";
$stmt = $conn->prepare($sqlPostDetails);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Post not found.</p>";
    exit;
}

$post = $result->fetch_assoc();

// Sprawdzenie własności
if (!$isLoggedIn || (int)$userId !== (int)$post['user_id']) {
    echo "<p>You are not authorized to edit this post.</p>";
    exit;
}

// Pobranie kategorii
$sqlCategories = "SELECT category_id, name FROM categories";
$categoriesResult = $conn->query($sqlCategories);

// Aktualizacja posta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_post'])) {
    $newTitle = $conn->real_escape_string($_POST['title']);
    $newContent = $conn->real_escape_string($_POST['content']);
    $newCategory = (int)$_POST['category'];
    
    $newImage = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $newImage = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    }
    
    $sqlUpdatePost = $newImage 
        ? "UPDATE posts SET title = ?, content = ?, category_id = ?, image = ? WHERE post_id = ?"
        : "UPDATE posts SET title = ?, content = ?, category_id = ? WHERE post_id = ?";
    
    $stmt = $conn->prepare($sqlUpdatePost);
    if ($newImage) {
        $stmt->bind_param("ssibi", $newTitle, $newContent, $newCategory, $newImage, $postId);
    } else {
        $stmt->bind_param("ssii", $newTitle, $newContent, $newCategory, $postId);
    }
    
    if ($stmt->execute()) {
        $_SESSION['edit_success'] = "Post updated successfully!";
        header("Location: post.php?id=$postId");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Post • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto max-w-4xl px-6 py-12">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-orange-400 mb-6">Edit Post</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="space-y-2">
                <label for="title" class="block text-lg font-semibold">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>" 
                       class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required>
            </div>

            <div class="space-y-2">
                <label for="content" class="block text-lg font-semibold">Content:</label>
                <textarea name="content" id="content" rows="6" 
                          class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required><?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="space-y-2">
                <label for="category" class="block text-lg font-semibold">Category:</label>
                <select name="category" id="category" class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required>
                    <?php while ($category = $categoriesResult->fetch_assoc()): ?>
                        <option value="<?php echo $category['category_id']; ?>" 
                                <?php echo $post['category_id'] == $category['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label for="image" class="block text-lg font-semibold">Upload New Image:</label>
                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-gray-400">
            </div>

            <button type="submit" name="edit_post" 
                    class="w-full py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400">
                Save Changes
            </button>
        </form>
    </div>
</main>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
