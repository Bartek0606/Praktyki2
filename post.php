<?php
session_start(); 

include 'db_connection.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy(); // Destroy the session
  header("Location: index.php"); // Redirect to homepage
  exit;
}

// Retrieve post ID from the URL
$postId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($postId > 0) {
    // Query to fetch post details
    $sqlPostDetails = "SELECT p.post_id, p.title, p.created_at, p.content, p.image, u.username, c.name AS category_name, p.is_question 
                       FROM posts p
                       LEFT JOIN users u ON p.user_id = u.user_id
                       LEFT JOIN categories c ON p.category_id = c.category_id
                       WHERE p.post_id = $postId";

    $resultPostDetails = $conn->query($sqlPostDetails);

    if ($resultPostDetails->num_rows > 0) {
        $post = $resultPostDetails->fetch_assoc();
    } else {
        echo "<p>Post not found.</p>";
        exit;
    }

    // Query to fetch comments related to the post
    $sqlComments = "SELECT c.content, c.created_at, u.username 
                    FROM comments c
                    LEFT JOIN users u ON c.user_id = u.user_id
                    WHERE c.post_id = $postId
                    ORDER BY c.created_at DESC";
    $resultComments = $conn->query($sqlComments);
} else {
    echo "<p>Invalid post ID.</p>";
    exit;
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment']) && $isLoggedIn) {
  $userId = $_SESSION['user_id']; 
  $commentContent = $conn->real_escape_string($_POST['comment_content']);
  
  // Get the next comment ID
  $sqlMaxCommentId = "SELECT MAX(comment_id) AS max_comment_id FROM comments";
  $resultMaxCommentId = $conn->query($sqlMaxCommentId);
  $rowMaxCommentId = $resultMaxCommentId->fetch_assoc();
  $nextCommentId = $rowMaxCommentId['max_comment_id'] + 1; 
  
  // Insert the new comment
  $sqlInsertComment = "INSERT INTO comments (comment_id, post_id, user_id, content) VALUES ($nextCommentId, $postId, $userId, '$commentContent')";
  
  if ($conn->query($sqlInsertComment) === TRUE) {
    $_SESSION['comment_success'] = 'Your comment has been posted successfully!';
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
  } else {
    echo "<p>Error: " . $conn->error . "</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="post.css">
    <link rel="stylesheet" href="navbar.css">
    <script src="post.js" defer></script>
    <title><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></title> <!-- Dynamic title -->
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">
        <h1><a href="index.php">HobbyHub</a></h1>
      </div>

      <div class="auth-buttons">
        <?php if ($isLoggedIn): ?>
          <div class="auth-info">
            <button class="btn new-post-btn" onclick="window.location.href='new_post.php'">New Post</button>
            <a href="profile.php" class="profile-link">
              <?php
              
                $sqlProfilePicture = "SELECT profile_picture FROM users WHERE user_id = '$userId'";
                $resultProfilePicture = $conn->query($sqlProfilePicture);
                $profilePictureSrc = 'default.png'; // Default image
                if ($resultProfilePicture->num_rows > 0) {
                    $userProfile = $resultProfilePicture->fetch_assoc();
                    if (!empty($userProfile['profile_picture'])) {
                        // If there's a profile picture, use it
                        $profilePictureSrc = 'data:image/jpeg;base64,' . base64_encode($userProfile['profile_picture']);
                    }
                }
              ?>
              <img src="<?php echo $profilePictureSrc; ?>" alt="Profile Picture" class="profile-img">
              <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </a>
          </div>
          
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
                <h1><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>By:</strong> <?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <!-- Display post image if it exists -->
            <?php if (!empty($post['image'])): ?>
                <?php $imageSrc = 'data:image/jpeg;base64,' . base64_encode($post['image']); ?>
                <img src="<?php echo $imageSrc; ?>" alt="Post Image" class="post-image">
            <?php endif; ?>
        </div>

        <br>

        <!-- Highlight if it's a question -->
        <?php if ($post['is_question']): ?>
            <div class="question-highlight">
                <strong>Question:</strong>
            </div>
        <?php endif; ?>

        <!-- Display post content -->
        <p><?php echo $post['content']; ?></p>
    </div>

    <!-- Display comments section -->
    <div class="comments-section">
        <h2>Comments</h2>

        <!-- Display the comment form only if the user is logged in -->
        <form method="POST" class="comment-form" onsubmit="checkLoginStatus(event)">
            <textarea name="comment_content" placeholder="Write your comment..." required class="comment-input"></textarea>
            <button type="submit" name="submit_comment" class="btn comment-btn">Post Comment</button>
        </form>

        <br>

        <!-- Display success message if set -->
        <?php if (isset($_SESSION['comment_success'])): ?>
            <div class="success-message"><?php echo $_SESSION['comment_success']; ?></div>
            <?php unset($_SESSION['comment_success']); // Clear the success message after displaying ?>
        <?php endif; ?>

        <?php if (!$isLoggedIn): ?>
            <!-- Display message if not logged in -->
            <p id="error-message" class="error-message"><strong>You must be logged in to post a comment</strong></p>
        <?php endif; ?>

        <br>  

        <!-- Display existing comments -->
        <?php if ($resultComments->num_rows > 0): ?>
            <?php while ($comment = $resultComments->fetch_assoc()): ?>
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

<!-- Hidden input to pass login status to JS -->
<input type="hidden" id="isLoggedIn" value="<?php echo $isLoggedIn ? 'true' : 'false'; ?>" />
</body>
</html>

<?php
$conn->close();
?>
