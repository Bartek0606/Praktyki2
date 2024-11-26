<?php
ob_start();
session_start();

include 'db_connection.php';
include 'Component/navbar.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

// Pobierz `user_id` z URL-a
if (!isset($_GET['id'])) {
    echo "User ID not specified.";
    exit();
}

$profileUserId = intval($_GET['id']);

// Pobierz dane użytkownika
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

// Pobierz posty użytkownika
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
    <!-- Informacje o użytkowniku -->
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
        </div>
    </div>

    <!-- Posty użytkownika -->
    <div class="container posts-container">
        <h2><?php echo htmlspecialchars($user['username']); ?>'s Posts</h2>
        <?php if ($result_posts->num_rows > 0): ?>
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
                                    <strong>Date: </strong><?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No posts available for this user.</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
