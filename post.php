<?php
ob_start();

session_start();

include 'db_connection.php';
include 'Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);

$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Retrieve post ID from the URL
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($postId > 0) {
    // Query to fetch post details
    $sqlPostDetails = "SELECT p.post_id, p.title, p.created_at, p.content, p.image, u.user_id, u.username, c.name AS category_name, p.is_question 
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

    // Query to fetch main comments related to the post
    $sqlComments = "SELECT c.comment_id, c.content, c.created_at, u.user_id, u.username, c.parent_comment_id 
                    FROM comments c
                    LEFT JOIN users u ON c.user_id = u.user_id
                    WHERE c.post_id = $postId AND c.parent_comment_id IS NULL
                    ORDER BY c.created_at DESC";
    $resultComments = $conn->query($sqlComments);
} else {
    echo "<p>Invalid post ID.</p>";
    exit;
}

// Handle comment or reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_comment']) && $isLoggedIn) {
        $userId = $_SESSION['user_id'];
        $commentContent = $conn->real_escape_string($_POST['comment_content']);
        $parentCommentId = isset($_POST['parent_comment_id']) ? (int)$_POST['parent_comment_id'] : NULL;

        $sqlInsertComment = $parentCommentId === NULL
            ? "INSERT INTO comments (post_id, user_id, content, parent_comment_id) VALUES ($postId, $userId, '$commentContent', NULL)"
            : "INSERT INTO comments (post_id, user_id, content, parent_comment_id) VALUES ($postId, $userId, '$commentContent', $parentCommentId)";
        
        if ($conn->query($sqlInsertComment)) {
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
    <style>
        .username-link {
            text-decoration: none;
            font-weight: bold;
            color: black;
        }

        .username-link:hover {
            color: #444;
        }
    </style>
    <script>
        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById("reply-form-" + commentId);
            replyForm.style.display = (replyForm.style.display === "none" || replyForm.style.display === "") ? "block" : "none";
        }
    </script>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>

<main class="container">
    <div class="post-details">
        <div class="post-header">
            <div class="post-info">
                <h1><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>
                    <strong>By:</strong> 
                    <a href="user.php?id=<?php echo urlencode($post['user_id']); ?>" class="username-link">
                        <?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <?php if (!empty($post['image'])): ?>
                <?php $imageSrc = 'data:image/jpeg;base64,' . base64_encode($post['image']); ?>
                <img src="<?php echo $imageSrc; ?>" alt="Post Image" class="post-image">
            <?php endif; ?>
        </div>

        <?php if ($post['is_question']): ?>
            <div class="question-highlight">
                <strong>Question:</strong>
            </div>
        <?php endif; ?>

        <p><?php echo $post['content']; ?></p>
    </div>

    <div class="comments-section">
        <h2>Comments</h2>

        <?php if ($isLoggedIn): ?>
            <form method="POST" class="comment-form">
                <textarea name="comment_content" placeholder="Write your comment..." required class="comment-input"></textarea>
                <button type="submit" name="submit_comment" class="btn comment-btn">Post Comment</button>
            </form>
        <?php else: ?>
            <p class="error-message">You must be logged in to post a comment.</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['comment_success'])): ?>
            <p class="success-message"><?php echo $_SESSION['comment_success']; unset($_SESSION['comment_success']); ?></p>
        <?php endif; ?>

        <?php
        while ($comment = $resultComments->fetch_assoc()):
            ?>
            <div class='comment'>
                <div class='comment-content'>
                    <p>
                        <strong>
                            <a href="user.php?id=<?php echo urlencode($comment['user_id']); ?>" class="username-link">
                                <?php echo htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </strong>
                        <span class='comment-date'><?php echo htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </p>
                    <p><?php echo htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>

                <?php if ($isLoggedIn): ?>
                    <button class='btn reply-btn' onclick='toggleReplyForm(<?php echo $comment['comment_id']; ?>)'>Reply</button>
                    <form method='POST' id='reply-form-<?php echo $comment['comment_id']; ?>' style='display:none;' class='reply-form'>
                        <textarea name='comment_content' placeholder='Write your reply...' required class='reply-input'></textarea>
                        <input type='hidden' name='parent_comment_id' value='<?php echo $comment['comment_id']; ?>'>
                        <button type='submit' name='submit_comment' class='btn reply-submit-btn'>Post Reply</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</main>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
