<?php
include __DIR__ . '/../db_connection.php';
include 'Edit_Post.php'; 
include 'Post.php';
include 'delete_post.php';

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
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-panel">
    <header>
        <h1 class="tittle">HOBBYHUB</h1>
    </header>
    <aside class="sidebar">
            <hr class="hr_nav">
            <div class="profile-section">
                <div class="profile-pic"></div>
                <p class="username">Username</p>
            </div>
            <nav class="menu">
                <hr class="hrbutton">
               <a href="admin.php"> <button class="menu-button">Posts</button></a>
                <hr class="hrbutton">
               <a href="events.php"> <button class="menu-button">Events</button></a>
                 <hr class="hrbutton">
                <a href="comments.php"><button class="menu-button">Comments</button></a>
                 <hr class="hrbutton">
                 <a href="categories.php"> <button class="menu-button">Add new category</button></a>
                 <hr class="hrbutton">
            </nav>
            <hr class="hrbutton">
            <div class="sidebar-bottom">
        <button class="logout-button">
            <span>Log Out</span>
        </button>
    </div>
        </aside>
    <main class="dashboard">
    <h2>All Posts</h2>
<ul class="post-list">
<?php if (empty($posts)): ?>
    <li>No posts found.</li>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <li class="post-item">
            <?php if (!empty($post['image'])): ?>
                <div class="post-image">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image">
                </div>
            <?php endif; ?>

            <div class="post-content">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p class="cont"><?php echo ($post['content']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category_name']); ?></p>
                <p><em>Created at: <?php echo htmlspecialchars($post['created_at']); ?></em></p>
            </div>
            <button class="editpost_button" data-post-id="<?php echo $post['post_id']; ?>">Edit</button>
            <form method="post" style="display:inline;">
                <input type="hidden" name="delete_post_id" value="<?php echo $post['post_id']; ?>">
                <button type="submit" class="deletepost_button">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>

    </main>
</div>

<div id="overlay"></div>
<div id="popupModal">
    <h2>Edit Post</h2>
    <?php if ($postToEdit): ?>
        <form method="POST" action="">
            <input type="hidden" name="post_id" value="<?php echo $postToEdit['post_id']; ?>">

            <label for="editTitle">Title:</label>
            <input type="text" id="editTitle" name="editTitle" value="<?php echo htmlspecialchars($postToEdit['title']); ?>" required>

            <label for="editContent">Content:</label>
            <textarea id="editContent" name="editContent" required><?php echo ($postToEdit['content']); ?></textarea>

            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <?php
                $categoriesQuery = $conn->query("SELECT category_id, name FROM categories");
                while ($category = $categoriesQuery->fetch_assoc()) {
                    $selected = ($category['category_id'] == $postToEdit['category_id']) ? 'selected' : '';
                    echo "<option value=\"{$category['category_id']}\" $selected>" . htmlspecialchars($category['name']) . "</option>";
                }
                ?>
            </select>

            <button type="submit" name="saveChanges">Save changes</button>
            <button type="button" id="cancelEdit">Cancel</button>
        </form>
    <?php endif; ?>
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
    popupModal.style.display = "block";
    overlay.style.display = "block";
    <?php endif; ?>

    if (cancelEdit) {
        cancelEdit.addEventListener("click", () => {
            popupModal.style.display = "none";
            overlay.style.display = "none";
            window.location.href = "admin.php";
        });
    }
});
</script>
</body>
</html>
