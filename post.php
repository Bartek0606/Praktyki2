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

    // Query to fetch all comments (including replies) related to the post
    $sqlComments = "SELECT c.comment_id, c.content, c.created_at, u.user_id, u.username, c.parent_comment_id 
                    FROM comments c
                    LEFT JOIN users u ON c.user_id = u.user_id
                    WHERE c.post_id = $postId 
                    AND c.parent_comment_id IS NULL
                    ORDER BY c.created_at DESC";
    $resultComments = $conn->query($sqlComments);

    // Query to fetch replies (oldest first)
    $sqlReplies = "SELECT c.comment_id, c.content, c.created_at, u.user_id, u.username, c.parent_comment_id 
                   FROM comments c
                   LEFT JOIN users u ON c.user_id = u.user_id
                   WHERE c.post_id = $postId 
                   AND c.parent_comment_id IS NOT NULL
                   ORDER BY c.created_at ASC";
    $resultReplies = $conn->query($sqlReplies);

    // Create an array to store replies indexed by parent comment ID
    $replies = [];
    while ($reply = $resultReplies->fetch_assoc()) {
        $replies[$reply['parent_comment_id']][] = $reply;
    }
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
$image_src = '../default.png'; 

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (!empty($row['profile_picture']) && $row['profile_picture'] != 'default.png') {
        $image_src = 'data:image/png;base64,' . base64_encode($row['profile_picture']); 
    }
} else {
    $image_src = '../default.png';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="glowna.css">
    
    <link rel="stylesheet" href="navbar.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="post.js" defer></script>
    <title><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>

    <main class="container mx-auto p-6">
    <article class="bg-gray-100 p-6 rounded-lg border border-gray-200 shadow-md">
        <div class="flex justify-between items-center mb-5 text-gray-500">
            <span class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800">
                <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                </svg>
                Category: <?php echo htmlspecialchars($post['category_name'], ENT_QUOTES, 'UTF-8'); ?>
            </span>
            <span class="text-sm"><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
        </div>

        <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
            <?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>
        </h2>

        <?php if (!empty($post['image'])): ?>
            <?php $imageSrc = 'data:image/jpeg;base64,' . base64_encode($post['image']); ?>
            <img src="<?php echo $imageSrc; ?>" alt="Post Image" class="w-24 h-auto rounded-lg shadow-md mr-4 float-left">
        <?php endif; ?>
        
        <p class="mb-5 font-light text-gray-500">
            <?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img src="<?php echo htmlspecialchars($image_src, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Image" class="w-8 h-8 rounded-full">
                <span class="font-medium text-gray-900">
                    <?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </div>
        </div>
    </article>


    <div class="comments-section mt-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Comments</h2>

        <?php if ($isLoggedIn): ?>
            <form method="POST" class="comment-form mb-6">
                <textarea name="comment_content" placeholder="Write your comment..." required class="w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"></textarea>
                <button type="submit" name="submit_comment" class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-200">Post Comment</button>
            </form>
        <?php else: ?>
            <p class="text-red-600">You must be logged in to post a comment.</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['comment_success'])): ?>
            <p class="text-green-800 bg-green-100 p-4 rounded-md mb-4"><?php echo $_SESSION['comment_success']; unset($_SESSION['comment_success']); ?></p>
        <?php endif; ?>

        <?php
        // Display comments with replies nested under their respective parents
        while ($comment = $resultComments->fetch_assoc()) {
            echo "<div class='comment bg-gray-100 p-4 rounded-lg shadow-md mb-6'>";
            echo "<div class='comment-content mb-4'>";
            echo "<p><strong><a href='user.php?id=" . urlencode($comment['user_id']) . "' class='text-blue-600 hover:underline'>" . htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8') . "</a></strong> <span class='text-gray-500 text-sm'>" . htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8') . "</span></p>";
            echo "<p class='text-gray-700'>" . htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</div>";

            // Show the reply form immediately after the main comment, if logged in
            if ($isLoggedIn) {
                echo "<button class='mt-4 px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200' onclick='toggleReplyForm({$comment['comment_id']})'>Reply</button>";
                echo "<form method='POST' id='reply-form-{$comment['comment_id']}' style='display:none;' class='mt-4'>";
                echo "<textarea name='comment_content' placeholder='Write your reply...' required class='w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4'></textarea>";
                echo "<input type='hidden' name='parent_comment_id' value='{$comment['comment_id']}'>";
                echo "<button type='submit' name='submit_comment' class='px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200'>Post Reply</button>";
                echo "</form>";
            }

            // Check if there are replies to this comment
            if (isset($replies[$comment['comment_id']])) {
                foreach ($replies[$comment['comment_id']] as $reply) {
                    echo "<div class='comment reply mt-4 pl-6 bg-gray-200 p-4 rounded-lg shadow-sm'>";
                    echo "<div class='comment-content mb-4'>";
                    echo "<p><strong><a href='user.php?id=" . urlencode($reply['user_id']) . "' class='text-blue-600 hover:underline'>" . htmlspecialchars($reply['username'], ENT_QUOTES, 'UTF-8') . "</a></strong> <span class='text-gray-500 text-sm'>" . htmlspecialchars($reply['created_at'], ENT_QUOTES, 'UTF-8') . "</span></p>";
                    echo "<p class='text-gray-700'>" . htmlspecialchars($reply['content'], ENT_QUOTES, 'UTF-8') . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
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
ob_end_flush();
?>
