<?php
include __DIR__ . '/../db_connection.php';
include 'Post.php';
$post = new Post($conn);
$posts = $post->getAllPosts();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <script src="admin.js" defer></script>
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
                <button class="menu-button">Posts</button>
                <hr class="hrbutton">
                <button class="menu-button">Events</button>
                 <hr class="hrbutton">
                <button class="menu-button">Comments</button>
                 <hr class="hrbutton">
                <button class="menu-button">Add new categorry</button>
                 <hr class="hrbutton">
                           <div class="sidebar-bottom">
        <button class="logout-button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="logout_icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
            </svg>
            <span>Log Out</span>
        </button>
    </div>
            </nav>
             <hr class="hrbutton">
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
                <!-- Przycisk edycji z atrybutem data-post-id -->
                <button class="editpost_button" type="button" data-post-id="<?php echo $post['post_id']; ?>">Edytuj</button>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>


<!-- Overlay (przyciemnione tło) -->
<div id="overlay" style="display:none;"></div>

<!-- Popup Modal -->
<div id="popupModal" style="display:none;">
    <h2>Edit post</h2>
  <!-- Formularz edycji -->
<form id="editForm">
    <input type="text" id="editTitle" placeholder="Title">
    <textarea id="editContent" placeholder="Content"></textarea>
    <!-- Dodajemy dynamicznie generowany dropdown -->
    <select id="editCategory" name="category_id">
        <?php
        // Pobierz kategorie z bazy danych
        include __DIR__ . '/../db_connection.php';
        $categoriesQuery = $conn->query("SELECT category_id, name FROM categories");
        while ($category = $categoriesQuery->fetch_assoc()) {
            echo "<option value=\"{$category['category_id']}\">" . htmlspecialchars($category['name']) . "</option>";
        }
        ?>
    </select>
    <button type="submit">Save changes</button>
    <button type="button" id="closePopup">Cancel</button>
</form>

</div>
</main>
</div>
</body>
</html>
