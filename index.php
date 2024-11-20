<?php
session_start(); // Start session to check login status

// Include the database connection
include 'db_connection.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to homepage
    exit;
}

// Fetch blog posts from the database
$sql = "SELECT p.post_id, p.title, p.created_at, u.username, c.name AS category_name 
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.user_id
        LEFT JOIN categories c ON p.category_id = c.category_id
        ORDER BY p.created_at DESC"; // Sort by date, showing latest first

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="glowna.css">
    <script src="glowna.js" defer></script>
    <title>HobbyHub</title>
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">
        <h1><a href="index.php">HobbyHub</a></h1>
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
        <?php if ($isLoggedIn): ?>
          <span class="welcome-message"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <form method="POST" style="display: inline;">
                <button type="submit" name="logout" class="btn logout-btn">Log out</button>
            </form>
            
        <?php else: ?>
            <button class="btn register-btn" onclick="window.location.href='register.php'">Register</button>
            <button class="btn login-btn" onclick="window.location.href='login.php'">Login</button>
        <?php endif; ?>
      </div>
    </nav>
  </header>

  <main class="container">
    <div class="nagl">
      <h2>Nasze najnowsze wydarzenia</h2>
      <hr class="divider">
    </div>

    <div class="event-slider-container">
      <button class="arrow-btn left-btn">←</button>
      <div class="event-slider">
        <!-- Add event cards dynamically here if needed -->
      </div>
      <button class="arrow-btn right-btn">→</button>
    </div>

    <div class="nagl">
      <h2 class="tytul_kategorii">Ostatnie posty na blogu</h2>
      <hr class="divider">
    </div>

    <section class="blog-posts">
      <div class="blog-grid">
        <?php
        if ($result->num_rows > 0) {
            // Output the data for each blog post
            while ($row = $result->fetch_assoc()) {
                echo "<div class='blog-post' onclick=\"location.href='post.php?id=" . $row['post_id'] . "'\">";
                echo "<img src='zdjecie.png' alt='Post Image'>"; // Replace with actual image URL if available
                echo "<div class='blog-post-info'>";
                echo "<h3>" . $row['title'] . "</h3>";  // Display title instead of content
                echo "<p><strong>Category:</strong> " . $row['category_name'] . "</p>";
                echo "<p><strong>By:</strong> " . $row['username'] . "</p>";
                echo "<p><strong>Date:</strong> " . $row['created_at'] . "</p>"; // Date displayed below the username
                echo "</div></div>";
            }
        } else {
            echo "<p>No blog posts available.</p>";
        }
        ?>
      </div>
    </section>
  </main>

</body>
</html>

<?php
$conn->close();
?>
