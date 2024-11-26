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
    $sqlComments = "SELECT c.comment_id, c.content, c.created_at, u.username, c.parent_comment_id 
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
  
  // If it's a reply, set the parent comment ID
  $parentCommentId = isset($_POST['parent_comment_id']) ? (int)$_POST['parent_comment_id'] : NULL;
  
  // Insert the new comment
  $sqlInsertComment = "INSERT INTO comments (comment_id, post_id, user_id, content, parent_comment_id) 
                       VALUES ($nextCommentId, $postId, $userId, '$commentContent', $parentCommentId)";
  
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
    <title><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></title>
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
                // Fetch profile picture from the database
                $user_id = $_SESSION['user_id'];
                $sql_image = "SELECT profile_picture FROM users WHERE user_id = '$user_id'";
                $result_image = $conn->query($sql_image);
                $image_src = 'default.png'; // Default image
                if ($result_image->num_rows > 0) {
                    $row = $result_image->fetch_assoc();
                    if (!empty($row['profile_picture']) && $row['profile_picture'] !== 'default.png') {
                        $image_src = 'data:image/jpeg;base64,' . base64_encode($row['profile_picture']);
                    }
                }
              ?>
              <img src="<?php echo $image_src; ?>" alt="Profile Picture" class="profile-img">
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
        <form method="POST" class="comment-form">
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
            <p id="error-message" class="error-message"><strong>You must be logged in to post a comment</strong></p>
        <?php endif; ?>

        <br>  
      
<?php
// Create an associative array to hold comments
$commentsHierarchy = [];
$replies = [];

while ($comment = $resultComments->fetch_assoc()) {
    if ($comment['parent_comment_id'] == NULL) {
        // Parent comment
        $commentsHierarchy[$comment['comment_id']] = $comment;
    } else {
        // Reply to parent comment
        $replies[$comment['parent_comment_id']][] = $comment;
    }
}

// Display the comments and replies
foreach ($commentsHierarchy as $parentComment) {
    echo "<div class='comment'>";
    echo "<p><strong>" . htmlspecialchars($parentComment['username'], ENT_QUOTES, 'UTF-8') . "</strong> " . htmlspecialchars($parentComment['created_at'], ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p>" . nl2br(htmlspecialchars($parentComment['content'], ENT_QUOTES, 'UTF-8')) . "</p>";

    // Display replies if any (in reverse order)
    if (isset($replies[$parentComment['comment_id']])) {
        echo "<div class='replies'>";
        
        // Reverse the replies array for this particular comment
        $reversedReplies = array_reverse($replies[$parentComment['comment_id']]);
        
        foreach ($reversedReplies as $reply) {
            echo "<div class='reply'>";
            echo "<p><strong>" . htmlspecialchars($reply['username'], ENT_QUOTES, 'UTF-8') . "</strong> " . htmlspecialchars($reply['created_at'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p>" . nl2br(htmlspecialchars($reply['content'], ENT_QUOTES, 'UTF-8')) . "</p>";
            echo "</div>";
        }
        echo "</div>";
    }

    echo "</div><br>";
}

// If no comments, show a message
if (empty($commentsHierarchy)) {
    echo "<p>No comments yet. Be the first to comment!</p>";
}
?>


    </div>
  </main>
</body>
</html>
