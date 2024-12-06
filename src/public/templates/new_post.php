<?php
ob_start();
session_start();
include 'db_connection.php';
include 'Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);

$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category_id = (int) $_POST['category'];
    $is_question = isset($_POST['is_question']) ? 1 : 0;
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    }

    $sql = "INSERT INTO posts (user_id, title, content, category_id, is_question, image, created_at)
            VALUES ($user_id, '$title', '$content', $category_id, $is_question, '$image', NOW())";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['post_success'] = "Your post has been created successfully!";
        header("Location: index.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}

$categories_sql = "SELECT * FROM categories";
$categories_result = $conn->query($categories_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Create New Post • HobbyHub</title>
</head>
<body class="bg-gray-900 text-white">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto max-w-4xl px-6 py-12">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-orange-400 mb-6">Create New Post</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="space-y-2">
                <label for="title" class="block text-lg font-semibold">Title:</label>
                <input type="text" name="title" id="title" class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required>
            </div>

            <div class="space-y-2">
                <label for="content" class="block text-lg font-semibold">Content:</label>
                <textarea name="content" id="content" rows="6" class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required></textarea>
            </div>

            <div class="space-y-2">
                <label for="category" class="block text-lg font-semibold">Category:</label>
                <select name="category" id="category" class="w-full p-3 bg-gray-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400" required>
                    <?php while ($category = $categories_result->fetch_assoc()): ?>
                        <option value="<?php echo $category['category_id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="flex items-center space-x-3">
                <input type="checkbox" name="is_question" id="is_question" value="1" class="h-5 w-5 text-orange-400 bg-gray-700 rounded border-gray-600 focus:ring-orange-400">
                <label for="is_question" class="text-lg">Is this a question?</label>
            </div>

            <div class="space-y-2">
                <label for="image" class="block text-lg font-semibold">Upload Image:</label>
                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-gray-400">
            </div>

            <button type="submit" name="submit_post" class="w-full py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400">Post</button>
        </form>
    </div>
</main>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
