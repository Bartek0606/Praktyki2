<?php
// Wczytanie połączenia z bazą danych oraz klasy Comment
include __DIR__ . '/../db_connection.php';  // Połączenie z bazą danych
include 'Comment.php';  // Klasa Comment

// Pobranie danych z formularza wyszukiwania (jeśli zostały wysłane)
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Utworzenie obiektu klasy Comment i pobranie komentarzy
$commentObj = new Comment($conn);
$comments = $commentObj->getAllPCom($search_query);  // Pobranie komentarzy z możliwością filtrowania

// Sprawdzamy, czy został wysłany request do usunięcia komentarza
if (isset($_GET['delete_comment_id'])) {
    $comment_id = (int)$_GET['delete_comment_id'];
    // Usuwamy komentarz
    $commentObj->deleteComment($comment_id);
}
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
                <button class="menu-button">Add new category</button>
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
        <h2>All Comments</h2>

       <div id="serachdiv">
 <!-- Formularz wyszukiwania -->
        <form method="GET" class="search-form">
            <input type="text" name="search_query" placeholder="Search by full name" value="<?php echo htmlspecialchars($search_query); ?>" />
            <button type="submit">Search</button>
        </form>
       </div>

        <div class="comments-container">
            <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comment-header">
                            <p class="comment-user">User: <?php echo htmlspecialchars($comment['full_name']); ?></p>
                            <p class="comment-date"><?php echo date('Y-m-d H:i:s', strtotime($comment['created_at'])); ?></p>
                        </div>
                        <div class="comment-body">
                            <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                        </div>
                        <div class="comment-footer">
                            <a href="?delete_comment_id=<?php echo $comment['comment_id']; ?>" 
                               class="delete-button" onclick="return confirm('Are you sure you want to delete this comment?');">
                               Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No comments found.</p>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>
