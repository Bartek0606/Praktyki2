<?php
// Include the database connection
include 'db_connection.php';

// Fetch blog posts from the database
$sql = "SELECT p.post_id, p.content, p.created_at, u.username, c.name AS category_name 
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.user_id
        LEFT JOIN categories c ON p.category_id = c.category_id
        ORDER BY p.created_at DESC LIMIT 3"; // Limit to the latest 3 posts

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="glowna.css">
    <script src="glowna.js" defer></script>
    <title>Strona główna</title>
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
                echo "<div class='blog-post'>";
                echo "<img src='zdjecie.png' alt='Post Image'>"; // Replace with actual image URL if available
                echo "<div class='blog-post-info'>";
                echo "<h3>" . $row['content'] . "</h3>";  // Short excerpt or title
                echo "<p><strong>Category:</strong> " . $row['category_name'] . "</p>";
                echo "<p><strong>By:</strong> " . $row['username'] . " | " . $row['created_at'] . "</p>";
                echo "<a href='post.php?id=" . $row['post_id'] . "'>Więcej</a>"; // Link to the full post page
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
