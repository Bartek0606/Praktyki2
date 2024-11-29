<?php
include __DIR__ . '/../db_connection.php';
include 'Edit_Post.php'; 
include 'Post.php';
include 'delete_post.php';
include_once 'sidebar_admin.php';

// Tworzenie obiektu klasy Sidebar
$sidebar = new Sidebar();

// Pobieranie wszystkich postów
$post = new Post($conn);
$posts = $post->getAllPosts();

$adminPanel = new Edit_Post($conn);

// Pobieranie wszystkich postów
$posts = $adminPanel->getAllPosts();

// Pobieranie kategorii do listy w formularzu
$categories = $adminPanel->getCategories();

// Obsługa zapisania zmian w formularzu edycji
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveChanges'])) {
    $isUpdated = $adminPanel->handleSaveChanges($_POST);
    if ($isUpdated) {
        // Przekierowanie po zapisaniu zmian
        $adminPanel->redirectAfterSave();
    } else {
        echo "Błąd podczas zapisywania danych.";
    }
}

// Pobieranie danych posta do edycji
$postToEdit = null;
if (isset($_GET['edit_post_id'])) {
    $editPostId = intval($_GET['edit_post_id']);
    $postToEdit = $adminPanel->getPostToEdit($editPostId);
}

$postManager = new Delete_post($conn);

// Obsługa usuwania posta przez wywołanie odpowiedniej metody klasy
$postManager->handleDeleteRequest();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
    @tailwind base;
@tailwind components;
@tailwind utilities;

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-in-out;
}

</style>
<body>
<div class="admin-panel">
   <?php $sidebar->render(); ?>

   <main class="dashboard p-8 bg-gray-50 ml-64 min-h-screen" style="padding-top: 6rem;">

    <h2 class="text-xl font-semibold text-center text-gray-800 mt-3 mb-4">All Posts</h2>
    <ul class="post-list m-auto space-y-4 w-4/6 mt-3">
        <?php if (empty($posts)): ?>
            <li class="text-gray-500 italic">No posts found.</li>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <li class="post-item bg-white shadow-md rounded-lg p-4 flex items-start relative">
                    <!-- Post Image -->
                    <?php if (!empty($post['image'])): ?>
                        <div class="post-image w-1/6 mr-5 h-full rounded-md overflow-hidden">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>

                    <!-- Post Content -->
                    <div class="post-content flex-1">
                        <h3 class="text-lg font-semibold text-gray-700"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="text-gray-600 mt-3"><?php echo ($post['content']); ?></p>
                        <p class="text-sm text-gray-500 mt-3"><strong>Category:</strong> <?php echo htmlspecialchars($post['category_name']); ?></p>
                        <p class="text-sm text-gray-400 mt-3"><em>Created at: <?php echo htmlspecialchars($post['created_at']); ?></em></p>
                    </div>

                    <!-- Actions -->
                    <div class="actions absolute bottom-4 right-4 flex space-x-2">
                        <button class="editpost_button bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200" data-post-id="<?php echo $post['post_id']; ?>">Edit</button>
                        <form method="post" class="inline">
                            <input type="hidden" name="delete_post_id" value="<?php echo $post['post_id']; ?>">
                            <button type="submit" class="deletepost_button bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-200">Delete</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</main>

</div>

<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>

<!-- Popup do edycji -->
<div id="popupModal" class="fixed inset-0 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Post</h2>

        <?php if ($postToEdit): ?>
        <form method="POST" action="" class="space-y-4">
            <input type="hidden" name="post_id" value="<?php echo $postToEdit['post_id']; ?>">
            <div>
                <label for="editTitle" class="block text-gray-700 font-medium">Title:</label>
                <input type="text" id="editTitle" name="editTitle" value="<?php echo htmlspecialchars($postToEdit['title']); ?>" required 
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none">
                </div>
            <div>
                <label for="editContent" class="block text-gray-700 font-medium">Content:</label>
                <textarea
                    id="editContent"
                    name="editContent"
                    required
                    class="w-full h-32 px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none">
                    <?php echo ($postToEdit['content']); ?></textarea>
            </div>
            <div>
                <label for="category_id" class="block text-gray-700 font-medium">Category:</label>
                <select
                    id="category_id"
                    name="category_id"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none"
                >
                    <?php
                    $categoriesQuery = $conn->query("SELECT category_id, name FROM categories");
                    while ($category = $categoriesQuery->fetch_assoc()) {
                        $selected = ($category['category_id'] == $postToEdit['category_id']) ? 'selected' : '';
                        echo "<option value=\"{$category['category_id']}\" $selected>" . htmlspecialchars($category['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="flex justify-end space-x-4">
                <button
                    type="button"
                    id="cancelEdit"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400"
                > Cancel
                </button>
                <button
                    type="submit"
                    name="saveChanges"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                > Save Changes
                </button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".editpost_button");
    const popupModal = document.getElementById("popupModal");
    const overlay = document.getElementById("overlay");
    const cancelEdit = document.getElementById("cancelEdit");

    editButtons.forEach(button => {
        button.addEventListener("click", () => {
            const postId = button.getAttribute("data-post-id");
            window.location.href = "?edit_post_id=" + postId;
        });
    });

    <?php if ($postToEdit): ?>
    popupModal.classList.remove("hidden");
    overlay.classList.remove("hidden");
    <?php endif; ?>

    if (cancelEdit) {
        cancelEdit.addEventListener("click", () => {
            popupModal.classList.add("hidden");
            overlay.classList.add("hidden");
            window.location.href = "admin.php";
        });
    }
});

</script>
</body>
</html>
