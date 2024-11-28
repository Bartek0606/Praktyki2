<?php
ob_start();
session_start();

include 'db_connection.php';
include 'Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to homepage
    exit;
}

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Get the `user_id` from the URL
if (!isset($_GET['id'])) {
    echo "User ID not specified.";
    exit();
}

$profileUserId = intval($_GET['id']);

// Get user data
$sql_user = "SELECT username, email, full_name, bio, profile_picture FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $profileUserId);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Get user's posts
$sql_posts = "
    SELECT posts.post_id, posts.title, posts.content, posts.image, posts.created_at, categories.name AS category_name 
    FROM posts 
    LEFT JOIN categories ON posts.category_id = categories.category_id 
    WHERE posts.user_id = ? 
    ORDER BY posts.created_at DESC
";
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bind_param("i", $profileUserId);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();

// Check if the user is already being followed
$isFollowing = false;
if ($isLoggedIn) {
    $sql_follow_check = "SELECT * FROM user_follows WHERE follower_id = ? AND following_id = ?";
    $stmt_follow_check = $conn->prepare($sql_follow_check);
    $stmt_follow_check->bind_param("ii", $userId, $profileUserId);
    $stmt_follow_check->execute();
    $result_follow_check = $stmt_follow_check->get_result();
    $isFollowing = $result_follow_check->num_rows > 0;
}

// Get the number of followers
$sql_followers_count = "SELECT COUNT(*) AS followers_count FROM user_follows WHERE following_id = ?";
$stmt_followers_count = $conn->prepare($sql_followers_count);
$stmt_followers_count->bind_param("i", $profileUserId);
$stmt_followers_count->execute();
$result_followers_count = $stmt_followers_count->get_result();
$followers_count = $result_followers_count->fetch_assoc()['followers_count'];

// Get the number of users the profile user is following
$sql_following_count = "SELECT COUNT(*) AS following_count FROM user_follows WHERE follower_id = ?";
$stmt_following_count = $conn->prepare($sql_following_count);
$stmt_following_count->bind_param("i", $profileUserId);
$stmt_following_count->execute();
$result_following_count = $stmt_following_count->get_result();
$following_count = $result_following_count->fetch_assoc()['following_count'];

// Handle follow/unfollow action
if ($isLoggedIn && isset($_POST['follow'])) {
    if ($isFollowing) {
        // Unfollow
        $sql_unfollow = "DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?";
        $stmt_unfollow = $conn->prepare($sql_unfollow);
        $stmt_unfollow->bind_param("ii", $userId, $profileUserId);
        $stmt_unfollow->execute();
    } else {
        // Follow
        $sql_follow = "INSERT INTO user_follows (follower_id, following_id) VALUES (?, ?)";
        $stmt_follow = $conn->prepare($sql_follow);
        $stmt_follow->bind_param("ii", $userId, $profileUserId);
        $stmt_follow->execute();
    }
    // Refresh the page to reflect the follow/unfollow change
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $profileUserId);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="navbar.css">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile • HobbyHub</title>
</head>
<body>
<header>
    <?php echo $navbar->render(); ?>
</header>
<main>
    <!-- User information -->
    <div class="container user-info">
        <div class="user-profile">
            <?php if ($user['profile_picture'] && $user['profile_picture'] !== 'default.png'): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
            <?php else: ?>
                <img src="default.png" alt="Default Profile Picture">
            <?php endif; ?>
        </div>
        <div class="user-details">
    <h2><?php echo htmlspecialchars($user['username']); ?></h2>
    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
    <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
    <p><strong>Followers: </strong><?php echo $followers_count; ?> <strong>Following: </strong><?php echo $following_count; ?></p>

    <!-- Only show Follow button if the user is logged in and is not following the profile user -->
    <?php if ($isLoggedIn && $userId != $profileUserId): ?>
        <form method="POST">
            <button type="submit" name="follow" class="follow-btn <?php echo $isFollowing ? 'unfollow' : ''; ?>">
                <?php echo $isFollowing ? 'Unfollow' : 'Follow'; ?>
            </button>
        </form>
    <?php endif; ?>

    <!-- Show Edit Profile button if viewing own profile -->
    <?php if ($isLoggedIn && $userId == $profileUserId): ?>
        <a href="profile.php" class="edit-profile-btn">
            <button class="btn edit-btn">Edit Profile</button>
        </a>
    <?php endif; ?>
</div>

    </div>

    <div class="toggle-buttons">
    <button id="show-posts" class="toggle-btn">Your Posts</button>
    <button id="show-likes" class="toggle-btn">Your Likes</button>
</div>

    <!-- Posty użytkownika -->
    <div class="container posts-container" id="posts-container">
        <h2>Your Posts</h2>
        <?php
        $sql_posts = "
            SELECT posts.post_id, posts.title, posts.content, posts.image, posts.created_at, categories.name AS category_name 
            FROM posts 
            LEFT JOIN categories ON posts.category_id = categories.category_id 
            WHERE posts.user_id = '$userId' 
            ORDER BY posts.created_at DESC
        ";
        $result_posts = $conn->query($sql_posts);

        if ($result_posts->num_rows > 0): ?>
            <div class="posts">
                <?php while ($post = $result_posts->fetch_assoc()): ?>
                    <a href="post.php?id=<?php echo $post['post_id']; ?>" class="post-link">
                        <div class="post">
                            <?php if (!empty($post['image'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" class="post-image">
                            <?php endif; ?>
                            <div class="post-content">
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p class="category"><strong>Category: <?php echo htmlspecialchars($post['category_name']); ?></strong></p>
                                <p><?php echo htmlspecialchars($post['content']); ?></p>
                                <div class="post-date">
                                    <strong>Date: </strong><?php echo htmlspecialchars($post['created_at']); ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No posts yet. Start creating posts!</p>
        <?php endif; ?>
    </div>
    <div class="container posts-container" id="likes-container" style="display: none;">
   
        <h2>Your Like</h2>
        <?php
        $sql_like = "
        SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name,
            COUNT(user_likes.id_likes) AS like_count,users.user_id, users.username
            FROM posts
            JOIN categories ON posts.category_id = categories.category_id
            LEFT JOIN user_likes ON posts.post_id = user_likes.id_post
            LEFT JOIN users ON user_likes.id_user = users.user_id
            WHERE users.user_id = ?
            GROUP BY posts.post_id, users.user_id, users.username, categories.name
            ORDER BY posts.created_at DESC
    ";
    $stmt_like = $conn->prepare($sql_like);
    $stmt_like->bind_param("i", $userId);
    $stmt_like->execute();
    $result_like = $stmt_like->get_result();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
        $post_id = $_POST['post_id']; 
        $sql_check = "SELECT * FROM `user_likes` WHERE id_user = ? AND id_post = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $userId, $post_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows > 0) {
            $sql_delete = "DELETE FROM `user_likes` WHERE id_user = ? AND id_post = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("ii", $userId, $post_id);
            $stmt_delete->execute();
        } else {
            $sql_register = "INSERT INTO `user_likes`(`id_user`, `id_post`) VALUES (?, ?)";
            $stmt_register = $conn->prepare($sql_register);
            $stmt_register->bind_param("ii", $userId, $post_id);
            $stmt_register->execute();
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

 

        if ($result_like->num_rows > 0): ?>
            <div class="posts">
                <?php while ($like = $result_like->fetch_assoc()): ?>
                    <a href="post.php?id=<?php echo $like['post_id']; ?>" class="post-link">
                        <div class="post">
                            <?php if (!empty($like['image'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($like['image']); ?>" alt="Post Image" class="post-image">
                            <?php endif; ?>
                            <div class="post-content">
                                <h3><?php echo htmlspecialchars($like['title']); ?></h3>
                                <p class="category"><strong>Category: <?php echo htmlspecialchars($like['category_name']); ?></strong></p>
                                <p><?php echo htmlspecialchars($like['content']); ?></p>
                                <div class="post-date">
                                    <strong>Date: </strong><?php echo htmlspecialchars($like['created_at']); ?>
                                </div>
                                <form method="POST" action="">
                                    <div>Likes: <?php echo $like['like_count']; ?></div> 
                                    <input type="hidden" name="post_id" value="<?php echo $like['post_id']; ?>">
                                    <button class="heart" name="like" ></button>
                                </form>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No posts yet. Start creating posts!</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
<script>
    document.getElementById('show-posts').addEventListener('click', function() {
        document.getElementById('posts-container').style.display = 'block';
        document.getElementById('likes-container').style.display = 'none';
    });

    document.getElementById('show-likes').addEventListener('click', function() {
        document.getElementById('posts-container').style.display = 'none';
        document.getElementById('likes-container').style.display = 'block';
    });
</script>