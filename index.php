<?php
// Include the database connection
include 'db_connection.php';

// Fetch all blog posts from the database
$sql = "SELECT p.post_id, p.content, p.created_at, u.username, c.name AS category_name, p.image_url, p.is_question
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.user_id
        LEFT JOIN categories c ON p.category_id = c.category_id
        ORDER BY p.created_at DESC";  // No LIMIT here, it will fetch all posts

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
        <!-- Add event cards dynamically if needed -->
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
                $is_question = $row['is_question'];  // Check if the post is a question
                $post_class = $is_question ? 'question-post' : 'regular-post';  // Add a different class for questions
                
                echo "<div class='blog-post $post_class'>";
                
                // Check if an image URL exists and display the image with alt text
                if (!empty($row['image_url'])) {
                    // Use the post title or a description as alt text
                    $alt_text = "Image for post: " . htmlspecialchars($row['content']);
                    echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='" . $alt_text . "'>";
                } else {
                    $alt_text = "Default image for the blog post";
                    echo "<img src='default-image.png' alt='" . $alt_text . "'>"; // Placeholder image if no image is found
                }

                echo "<div class='blog-post-info'>";
                echo "<h3>" . htmlspecialchars($row['content']) . "</h3>";  // Short excerpt or title
                echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category_name']) . "</p>";
                echo "<p><strong>By:</strong> " . htmlspecialchars($row['username']) . " | " . htmlspecialchars($row['created_at']) . "</p>";
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
