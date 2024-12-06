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

function isOwner($userId, $ownerId) {
    return $userId === $ownerId;
}



// Retrieve post ID from the URL
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($postId > 0) {
    // Query to fetch post details
    $sqlPostDetails = "SELECT p.post_id, p.title, p.created_at, p.content, p.image, u.user_id, u.username,c.category_id AS category_id, c.name AS category_name, p.is_question 
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
function getUserImage($userId, $conn) { 
    $image_src = '../default.png'; 
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE user_id = ?"); 
    $stmt->bind_param("i", $userId); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    if ($result->num_rows > 0) { 
        $row = $result->fetch_assoc(); 
        if (!empty($row['profile_picture']) && $row['profile_picture'] != 'default.png') { 
            $image_src = 'data:image/png;base64,' . base64_encode($row['profile_picture']); 
        } 
    } 
    $stmt->close(); return htmlspecialchars($image_src, ENT_QUOTES, 'UTF-8'); 
}

// Handle post or comment editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_post']) && $isLoggedIn && isOwner($userId, $post['user_id'])) {
        $newContent = $conn->real_escape_string($_POST['new_content']);
        $sqlUpdatePost = "UPDATE posts SET content = '$newContent' WHERE post_id = $postId";
        if ($conn->query($sqlUpdatePost)) {
            $_SESSION['edit_success'] = 'Post updated successfully!';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }

    if (isset($_POST['edit_comment']) && $isLoggedIn) {
        $commentId = (int)$_POST['comment_id'];
        $newContent = $conn->real_escape_string($_POST['new_content']);

        // Verify ownership
        $sqlCheckOwner = "SELECT user_id FROM comments WHERE comment_id = $commentId";
        $resultCheckOwner = $conn->query($sqlCheckOwner);
        if ($resultCheckOwner->num_rows > 0) {
            $commentOwner = $resultCheckOwner->fetch_assoc();
            if (isOwner($userId, $commentOwner['user_id'])) {
                $sqlUpdateComment = "UPDATE comments SET content = '$newContent' WHERE comment_id = $commentId";
                if ($conn->query($sqlUpdateComment)) {
                    $_SESSION['edit_success'] = 'Comment updated successfully!';
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit();
                } else {
                    echo "<p>Error: " . $conn->error . "</p>";
                }
            }
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="post.js" defer></script>
    <title><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body class="bg-gray-900">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main class="container mx-auto p-6">
    <article class="bg-gray-600 p-8 rounded-lg border border-gray-200 shadow-md"> 
        <div class="flex justify-between items-center mb-5 text-white"> 
            <h2 class="mb-4 text-3xl font-bold tracking-tight text-white"> 
                <?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?> 
            </h2> 
            <span class="text-sm"><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></span> 
        </div> 
        <span class="bg-primary-100 text-white text-lg font-medium inline-flex items-center rounded dark:bg-primary-200 dark:text-primary-800 mb-4"> 
            <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"> 
                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path> 
            </svg> Category: <?php echo '<a href="subpage.php?id='.urlencode($post['category_id']). '" class="text-white hover:underline">'. htmlspecialchars($post['category_name'], ENT_QUOTES, 'UTF-8').'</a>'; ?> 
        </span>
        <div class="flex justify-between items-center mb-4"> 
            <div class="flex items-center space-x-4"> 
                <img src="<?php echo getUserImage($post['user_id'], $conn); ?>" alt="Profile Image" class="w-10 h-10 rounded-full"> 
                <span class="font-medium text-white"> 
                    <?php echo'<a href="user.php?id='.urlencode($post['user_id']).'"class="text-white hover:underline">'. htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8').'</a>'; ?> 
                </span> 
            </div> 
        </div> 
        <div class="flex items-start mb-6"> 
            <?php if (!empty($post['image'])): ?> 
                <?php $imageSrc = 'data:image/jpeg;base64,' . base64_encode($post['image']); ?> 
                <img src="<?php echo $imageSrc; ?>" alt="Post Image" class="w-1/2 h-auto rounded-lg shadow-md mr-6"> 
            <?php endif; ?> 
                <p class="font-light text-xl leading-relaxed flex-1 text-white"> 
                    <?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?> 
                </p> 
        </div>

        <?php if ($isLoggedIn && isOwner($userId, $post['user_id'])): ?>
            <button class="mt-4 px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200" onclick="toggleEditForm('post')">Edit Post</button>
            <form method="POST" id="edit-post-form" style="display:none;" class="mt-4">
                <textarea name="new_content" required class="w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 mb-4"><?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                <button type="submit" name="edit_post" class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200">Save Changes</button>
            </form>
        <?php endif; ?>
    </article>

    <!-- Comment Section -->
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

        <!-- Success messages -->
        <?php if (isset($_SESSION['comment_success'])): ?>
            <p class="text-green-800 bg-green-100 p-4 rounded-md mb-4"><?php echo $_SESSION['comment_success']; unset($_SESSION['comment_success']); ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['edit_success'])): ?>
            <p class="text-green-800 bg-green-100 p-4 rounded-md mb-4"><?php echo $_SESSION['edit_success']; unset($_SESSION['edit_success']); ?></p>
        <?php endif; ?>

        <!-- Comments rendering -->
        <?php while ($comment = $resultComments->fetch_assoc()): ?>
            <div class="flex w-full justify-between border rounded-md mb-6">
                <div class="p-3">
                    <div class="flex gap-3 items-center">
                        <img src="<?php echo getUserImage($comment['user_id'], $conn); ?>" alt="Profile Image" class="w-8 h-8 rounded-full">
                        <h3 class="font-bold">
                            <?php echo '<a href="user.php?id=' . urlencode($comment['user_id']) . '" class="text-blue-600 hover:underline">' . htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8') . '</a>'; ?>
                            <br>
                            <span class="text-sm text-gray-400 font-normal"><?php echo htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </h3>
                    </div>

                    <p class="text-gray-600 mt-2">
                        <?php echo htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <!-- Buttons: Reply and Edit Comment -->
                    <div class="flex justify-start items-center mt-4">
                        <?php if ($isLoggedIn): ?>
                            <button class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200" onclick="toggleReplyForm(<?php echo $comment['comment_id']; ?>)">Reply</button>
                        <?php endif; ?>

                        <?php if ($isLoggedIn && isOwner($userId, $comment['user_id'])): ?>
                            <button class="ml-2 px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200" onclick="toggleEditForm(<?php echo $comment['comment_id']; ?>)">Edit Comment</button>
                        <?php endif; ?>
                    </div>

                    <!-- Reply Form -->
                    <?php if ($isLoggedIn): ?>
                        <form method="POST" id="reply-form-<?php echo $comment['comment_id']; ?>" style="display:none;" class="mt-4">
                            <textarea name="comment_content" placeholder="Write your reply..." required class="w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"></textarea>
                            <input type="hidden" name="parent_comment_id" value="<?php echo $comment['comment_id']; ?>">
                            <button type="submit" name="submit_comment" class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-green-600 transition duration-200">Post Reply</button>
                        </form>
                    <?php endif; ?>


                    <!-- Edit Comment Form -->
                    <?php if ($isLoggedIn && isOwner($userId, $comment['user_id'])): ?>
                        <form method="POST" id="edit-comment-form-<?php echo $comment['comment_id']; ?>" style="display:none;" class="mt-4">
                            <textarea name="new_content" required class="w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 mb-4"><?php echo htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                            <button type="submit" name="edit_comment" class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200">Save Changes</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Replies -->
            <?php if (isset($replies[$comment['comment_id']])): ?>
                <?php foreach ($replies[$comment['comment_id']] as $reply): ?>
                    <div class="flex w-full justify-between border ml-5 rounded-md mb-6">
                        <div class="p-3">
                            <div class="flex gap-3 items-center">
                                <img src="<?php echo getUserImage($reply['user_id'], $conn); ?>" alt="Profile Image" class="w-8 h-8 rounded-full">
                                <h3 class="font-bold">
                                    <?php echo '<a href="user.php?id=' . urlencode($reply['user_id']) . '" class="text-blue-600 hover:underline">' . htmlspecialchars($reply['username'], ENT_QUOTES, 'UTF-8') . '</a>'; ?>
                                    <br>
                                    <span class="text-sm text-gray-400 font-normal"><?php echo htmlspecialchars($reply['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </h3>
                            </div>
                            <p class="text-gray-600 mt-2">
                                <?php echo htmlspecialchars($reply['content'], ENT_QUOTES, 'UTF-8'); ?>
                            </p>

                            <!-- Edit Reply Button -->
                            <?php if ($isLoggedIn && isOwner($userId, $reply['user_id'])): ?>
                                <button class="ml-2 px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200" onclick="toggleEditForm(<?php echo $reply['comment_id']; ?>)">Edit Reply</button>
                            <?php endif; ?>

                            <!-- Edit Reply Form -->
                            <?php if ($isLoggedIn && isOwner($userId, $reply['user_id'])): ?>
                                <form method="POST" id="edit-reply-form-<?php echo $reply['comment_id']; ?>" style="display:none;" class="mt-4">
                                    <textarea name="new_content" required class="w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 mb-4"><?php echo htmlspecialchars($reply['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    <input type="hidden" name="reply_id" value="<?php echo $reply['comment_id']; ?>">
                                    <button type="submit" name="edit_reply" class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200">Save Changes</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>
</main>


</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
