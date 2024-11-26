<?php
// Połączenie z bazą danych
include __DIR__ . '/../db_connection.php';
// Wczytanie klasy Post
include 'Post.php';

// Utworzenie obiektu Post
$post = new Post($conn);

// Pobranie wszystkich postów
$posts = $post->getAllPosts();
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
  <!-- Wyświetlanie postów -->
  
  <h2>All Posts</h2>
<ul class="post-list">
    <?php if (empty($posts)): 
        ?>
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
                    <p class="cont"><?php echo($post['content']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category_name']); ?></p>
                    <p><em>Created at: <?php echo htmlspecialchars($post['created_at']); ?></em></p>
                </div>
                <button id="editpost_button" type="button">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon_edit">
  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
</svg>
                </button>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

            
        </main>
    </div>
</body>
</html>
