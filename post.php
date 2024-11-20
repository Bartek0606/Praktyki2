<?php
// Include the database connection
include 'db_connection.php';

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($post_id > 0) {
    // Fetch the post from the database including the content
    $sql = "SELECT p.post_id, p.title, p.created_at, p.content, u.username, c.name AS category_name 
            FROM posts p
            LEFT JOIN users u ON p.user_id = u.user_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.post_id = $post_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Post not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid post ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="glowna.css">
    <script src="glowna.js" defer></script>
    <title><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></title> <!-- Dynamic title -->
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">
        <h1><a href="index.php" class="logo-link">HobbyHub</a></h1> <!-- Updated link to index.php -->
      </div>
      <ul class="nav-links">
        <li><a href="#">Fotografia</a></li>
        <li><a href="#">Gaming</a></li>
        <li><a href="#">Gotowanie</a></li>
        <li><a href="#">Ogrodnictwo</a></li>
        <li><a href="#">Sporty zimowe</a></li>
        <li><a href="#">Sporty wodne</a></li>
      </ul>

      <div class="auth-buttons">    
        <button class="btn register-btn" onclick="window.location.href='register.php'">Register</button>
        <button class="btn login-btn" onclick="window.location.href='login.php'">Login</button>
      </div>
    </nav>
  </header>

  <main class="container">
    <div class="post-details">
        <h1><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></h1> <!-- Post title -->
        <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>By:</strong> <?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8'); ?></p> <!-- Date below the author -->

        <br />
        <!-- Display the content of the post, ensuring that HTML is rendered safely -->
        <p><?php echo $row['content']; ?></p> <!-- Content below the date -->
    </div>
  </main>

</body>
</html>

<?php
$conn->close();
?>
