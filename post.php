<?php
// Include the database connection
include 'db_connection.php';

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($post_id > 0) {
    // Fetch the post from the database
    $sql = "SELECT p.content, p.created_at, u.username, c.name AS category_name, p.image_url 
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
    <link rel="stylesheet" href="glowna.css">
    <script src="glowna.js" defer></script>
    <title>Post Details</title>
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">
        <h1>Rozwijaj z nami swoje pasje!</h1>
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
        <button class="btn register-btn">Register</button>
        <button class="btn login-btn">Login</button>
      </div>
    </nav>
  </header>

  <main class="container">
    <div class="post-details">
        <h1><?php echo htmlspecialchars($row['content']); ?></h1>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category_name']); ?></p>
        <p><strong>By:</strong> <?php echo htmlspecialchars($row['username']); ?> | <?php echo htmlspecialchars($row['created_at']); ?></p>
        
        <!-- Display the image with alt text if it exists -->
        <?php if (!empty($row['image_url'])): ?>
            <?php $alt_text = "Image for post: " . htmlspecialchars($row['content']); ?>
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo $alt_text; ?>">
        <?php else: ?>
            <img src="default-image.png" alt="Default image for the blog post"> <!-- Placeholder if no image exists -->
        <?php endif; ?>

        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p> <!-- Full post content -->
    </div>
  </main>

</body>
</html>

<?php
$conn->close();
?>
