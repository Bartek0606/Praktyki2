<?php
session_start(); // Start session to check login status

// Include the database connection
include 'db_connection.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($post_id > 0) {
    // Fetch the post from the database, including the image
    $sql = "SELECT p.post_id, p.title, p.created_at, p.content, p.image, u.username, c.name AS category_name, p.is_question 
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

    // Fetch comments for the post
    $comments_sql = "SELECT c.content, c.created_at, u.username 
                     FROM comments c
                     LEFT JOIN users u ON c.user_id = u.user_id
                     WHERE c.post_id = $post_id
                     ORDER BY c.created_at DESC";
    $comments_result = $conn->query($comments_sql);
} else {
    echo "<p>Invalid post ID.</p>";
    exit;
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment']) && !$isLoggedIn) {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="post.css">
    <script src="glowna.js" defer></script>
    <title><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></title> <!-- Dynamic title -->
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
            <button class="btn register-btn" onclick="window.location.href='register.php'">Sign up</button>
            <button class="btn login-btn" onclick="window.location.href='login.php'">Login</button>
        <?php endif; ?>
      </div>
    </nav>
  </header>

  <main class="container">
    <div class="post-details">
      <div class="post-header">
        <div class="post-info">
          <h1><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
          <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p><strong>By:</strong> <?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p><strong>Date:</strong> <?php echo htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <!-- Display post image if it exists -->
        <?php if (!empty($row['image'])): ?>
          <?php $image_src = 'data:image/jpeg;base64,' . base64_encode($row['image']); ?>
          <img src="<?php echo $image_src; ?>" alt="Post Image" class="post-image">
        <?php endif; ?>
      </div>

      <!-- Highlight if it's a question -->
      <?php if ($row['is_question']): ?>
        <br>

        <div class="question-highlight">
          <strong>Question:</strong>
        </div>
      <?php endif; ?>

      <br>

      <!-- Display post content -->
      <p><?php echo nl2br(htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8')); ?></p>
    </div>

    <!-- Display comments section -->
<div class="comments-section">
  <h2>Comments</h2>

  <!-- Display the comment form only if the user is logged in -->

    <form method="POST" class="comment-form">
      <textarea name="comment_content" placeholder="Write your comment..." required class="comment-input"></textarea>
      <button type="submit" name="submit_comment" class="btn comment-btn">Post Comment</button>
    </form>

  <?php if (!$isLoggedIn): ?>
    <!-- Display message if not logged in -->
    <p class="login-prompt"><strong>You must be logged in to post a comment</strong></p>
  <?php endif; ?>

  <br>  

  <!-- Display existing comments -->
  <?php if ($comments_result->num_rows > 0): ?>
    <?php while ($comment = $comments_result->fetch_assoc()): ?>
      <div class="comment">
        <p><strong><?php echo htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8'); ?></strong> <?php echo htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?php echo nl2br(htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
      <br>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No comments yet. Be the first to comment!</p>
  <?php endif; ?>
</div>


  </main>
</body>
</html>

<?php
$conn->close();
?>
