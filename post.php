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

    // Query to fetch main comments related to the post (parent_comment_id is NULL), sorted by creation date (newest first)
$sqlComments = "SELECT c.comment_id, c.content, c.created_at, u.username, c.parent_comment_id 
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.user_id
                WHERE c.post_id = $postId AND c.parent_comment_id IS NULL
                ORDER BY c.created_at DESC"; // Latest comments first
$resultComments = $conn->query($sqlComments);

} else {
    echo "<p>Invalid post ID.</p>";
    exit;
}

// Handle comment or reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_comment']) && $isLoggedIn) {
        // For main comments and replies
        $userId = $_SESSION['user_id'];
        $commentContent = $conn->real_escape_string($_POST['comment_content']);
        
        // Check if it's a reply or a main comment
        $parentCommentId = isset($_POST['parent_comment_id']) ? (int) $_POST['parent_comment_id'] : NULL;

        if ($parentCommentId === NULL) {
            // Insert main comment (parent_comment_id is NULL)
            $sqlInsertComment = "INSERT INTO comments (post_id, user_id, content, parent_comment_id) 
                                 VALUES ($postId, $userId, '$commentContent', NULL)";
            $insertSuccess = $conn->query($sqlInsertComment);
        } else {
            // Insert reply (parent_comment_id is set)
            $sqlInsertReply = "INSERT INTO comments (post_id, user_id, content, parent_comment_id) 
                               VALUES ($postId, $userId, '$commentContent', $parentCommentId)";
            $insertSuccess = $conn->query($sqlInsertReply);
        }

        if ($insertSuccess) {
            $_SESSION['comment_success'] = 'Your comment has been posted successfully!';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
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
    <title><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <script>
        // Function to toggle the reply textarea visibility
        function toggleReplyForm(commentId) {
            var replyForm = document.getElementById("reply-form-" + commentId);
            replyForm.style.display = (replyForm.style.display === "none" || replyForm.style.display === "") ? "block" : "none";
        }
    </script>
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

            <?php if (!empty($post['image'])): ?>
                <?php $imageSrc = 'data:image/jpeg;base64,' . base64_encode($post['image']); ?>
                <img src="<?php echo $imageSrc; ?>" alt="Post Image" class="post-image">
            <?php endif; ?>
        </div>

        <br>

        <?php if ($post['is_question']): ?>
            <div class="question-highlight">
                <strong>Question:</strong>
            </div>
        <?php endif; ?>

        <p><?php echo $post['content']; ?></p>
    </div>

    <div class="comments-section">
        <h2>Comments</h2>

        <form method="POST" class="comment-form">
            <textarea name="comment_content" placeholder="Write your comment..." required class="comment-input"></textarea>
            <button type="submit" name="submit_comment" class="btn comment-btn">Post Comment</button>
        </form>

        <br>
        
        <?php if (isset($_SESSION['comment_success'])): ?>
            <p class="success-message"><?php echo $_SESSION['comment_success']; unset($_SESSION['comment_success']); ?></p>
        <?php endif; ?>

        <br>

        <?php
        $comments = [];
        while ($row = $resultComments->fetch_assoc()) {
            $comments[] = $row;
        }

        foreach ($comments as $comment) {
            echo "<div class='comment'>";
            echo "<div class='comment-content'>";
            echo "<p><strong>" . htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8') . "</strong> <span class='comment-date'>" . htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8') . "</span></p>";
            echo "<p>" . htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</div>";

            if ($isLoggedIn) {
                echo "<button class='btn reply-btn' onclick='toggleReplyForm(" . $comment['comment_id'] . ")'>Reply</button>";
                echo "<form method='POST' id='reply-form-" . $comment['comment_id'] . "' style='display:none;' class='reply-form'>";
                echo "<textarea name='comment_content' placeholder='Write your reply...' required class='reply-input'></textarea>";
                echo "<input type='hidden' name='parent_comment_id' value='" . $comment['comment_id'] . "'>";
                echo "<button type='submit' name='submit_comment' class='btn reply-submit-btn'>Post Reply</button>";
                echo "</form>";
            }

            $sqlReplies = "SELECT r.comment_id, r.content, r.created_at, u.username
                           FROM comments r
                           LEFT JOIN users u ON r.user_id = u.user_id
                           WHERE r.parent_comment_id = " . (int) $comment['comment_id'] . "
                           ORDER BY r.created_at ASC";
            $resultReplies = $conn->query($sqlReplies);
            while ($reply = $resultReplies->fetch_assoc()) {
                echo "<div class='reply'>";
                echo "<p><strong>" . htmlspecialchars($reply['username'], ENT_QUOTES, 'UTF-8') . "</strong> <span class='reply-date'>" . htmlspecialchars($reply['created_at'], ENT_QUOTES, 'UTF-8') . "</span></p>";
                echo "<p>" . htmlspecialchars($reply['content'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "</div>";
            }

            echo "</div>";
        }
        ?>
    </div>
  </main>
</body>
</html>

<?php
$conn->close();
?>
