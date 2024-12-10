<?php
ob_start();
session_start();
 
include '../../../db_connection.php';
include '../../Component/navbar.php';
 
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
if ($stmt_user === false) {
    die('MySQL prepare error: ' . $conn->error);
}
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
    SELECT posts.post_id, posts.title, posts.content, posts.image, posts.created_at,
           categories.name AS category_name, users.username AS author_username
    FROM posts
    LEFT JOIN categories ON posts.category_id = categories.category_id
    LEFT JOIN users ON posts.user_id = users.user_id
    WHERE posts.user_id = ?
    ORDER BY posts.created_at DESC
";
 
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bind_param("i", $profileUserId);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();
 
// Get the user's events
$sql_events = "
    SELECT events.event_id, events.event_name, events.event_description, events.event_date, events.location
    FROM events
    JOIN event_registrations ON events.event_id = event_registrations.event_id
    WHERE event_registrations.user_id = ?
    ORDER BY events.event_date DESC;
";
 
$stmt_events = $conn->prepare($sql_events);
$stmt_events->bind_param("i", $profileUserId); // Change $userId to $profileUserId
$stmt_events->execute();
$result_events = $stmt_events->get_result();
 
// Get user's items
$sql_items = "
    SELECT items.item_id, items.name, items.description, items.image, items.price, items.created_at
    FROM items
    WHERE items.user_id = ?
    ORDER BY items.created_at DESC
";
 
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $profileUserId);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
 
 
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
 
// Get the number of posts
$sql_posts_count = "SELECT COUNT(*) AS posts_count FROM posts WHERE user_id = ?";
$stmt_posts_count = $conn->prepare($sql_posts_count);
$stmt_posts_count->bind_param("i", $profileUserId);
$stmt_posts_count->execute();
$result_posts_count = $stmt_posts_count->get_result();
$posts_count = $result_posts_count->fetch_assoc()['posts_count'];
 
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/user.js" defer></script>
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile • HobbyHub</title>
</head>
 
<body class="bg-gray-900">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main>
    
<div class="container mx-auto p-6 bg-gray-700 rounded-lg shadow-xl transform transition-all duration-500 ">
    <!-- Główna sekcja układu -->
    <div class="flex items-center">
    <!-- Lewa sekcja (zdjęcie profilowe i przycisk) -->
    <?php
$image_src = '/src/public/image/default.png';  // Zmienna z pełną ścieżką do default.png
?>
<div class="w-full flex flex-col items-center">
    <div class="user-profile mb-4">
    <?php if ($user['profile_picture'] && $user['profile_picture'] !== 'default.png'): ?>
        <img class="w-36 h-36 rounded-full shadow-md hover:shadow-xl transition-shadow duration-300" src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
    <?php else: ?>
        <img class="w-36 h-36 rounded-full shadow-md hover:shadow-xl transition-shadow duration-300" src="<?php echo $image_src; ?>" alt="Default Profile Picture">
    <?php endif; ?>
    </div>
    
    <div>
        <?php if ($isLoggedIn && $userId == $profileUserId): ?>
            <a href="edit_profile.php" class="inline-block">
                <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 transform hover:scale-105 rounded-md text-white transition-all duration-300">Edit Profile</button>
            </a>
        <?php endif; ?>
    </div>
    <div class="flex gap-4">
    <?php if ($isLoggedIn && $userId != $profileUserId): ?>
        <!-- Przycisk Follow -->
        <form method="POST" action="">
            <button name="follow" 
                class="h-10 px-4 py-2 rounded-md text-white transition-all duration-300 
                <?php echo $isFollowing ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'; ?>">
                <?php echo $isFollowing ? 'Unfollow' : 'Follow'; ?>
            </button>
        </form>

        <!-- Przycisk Wiadomość -->
        <a href="message.php?id=<?php echo $profileUserId; ?>" 
            class="h-10 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-all duration-300">
            Message
        </a>
    <?php endif; ?>
</div>

</div>
 
        <!-- Prawa sekcja (informacje o użytkowniku) -->
        <div class="w-3/4 ml-8">
            <div class="user-details">
                <h2 class="text-3xl font-bold mb-2 text-white"><?php echo htmlspecialchars($user['username']); ?></h2>
                <p class="text-lg text-gray-300 mb-4"><?php echo htmlspecialchars($user['full_name']); ?></p>  
                <!-- ?? 'User Role' -->
                <!-- Sekcja bio -->
                <div class="bio text-gray-300 mb-6">
                    <h3 class="text-xl font-semibold text-white">Bio:</h3>
                    <p><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                </div>
 
                <div class="follow-info flex space-x-8 text-white">
                <div>
                        <p class="text-lg font-semibold"><?php echo $posts_count; ?></p>
                        <p class="text-gray-300">
                            <button id="show-followers" class="hover:underline">Posts</button>
                        </p>
                    </div>
                    <div>
                        <p class="text-lg font-semibold"><?php echo $followers_count; ?></p>
                        <p class="text-gray-300">
                            <button id="show-followers" class="hover:underline">Followers</button>
                        </p>
                    </div>
                    <div>
                        <p class="text-lg font-semibold"><?php echo $following_count; ?></p>
                        <p class="text-gray-300">
                            <button id="show-following" class="hover:underline">Following</button>
                        </p>
                    </div>
                </div>
 
 
            </div>
        </div>
    </div>
 
    <div id="popup-container" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-gray-700 w-11/12 md:w-2/3 lg:w-1/2 p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 id="popup-title" class="text-xl font-bold text-white"></h3>
                <button id="close-popup" class="text-gray-400 hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <div id="popup-content" class="text-gray-300 space-y-4">
                <!-- Lista followers/following będzie dynamicznie ładowana -->
            </div>
        </div>
    </div>
 
    <!-- Sekcja przycisków na dole -->
    <div class="toggle-buttons flex justify-center mt-6 space-x-4">
        <button id="show-posts" class="toggle-btn px-4 py-2 bg-gray-800 hover:bg-gray-900 transform hover:scale-105 rounded-md text-white transition-all duration-300">
            <?php
            echo $isLoggedIn && $userId == $profileUserId ? "Your Posts" : htmlspecialchars($user['username']) . "'s Posts";
            ?>
        </button>
        <?php if ($isLoggedIn && $userId == $profileUserId): ?>
            <button id="show-likes" class="toggle-btn px-4 py-2 bg-gray-800 hover:bg-gray-900 transform hover:scale-105 rounded-md text-white transition-all duration-300">Your Likes</button>
        <?php endif; ?>
        <button id="show-events" class="toggle-btn px-4 py-2 bg-gray-800 hover:bg-gray-900 transform hover:scale-105 rounded-md text-white transition-all duration-300">
            <?php
            echo $isLoggedIn && $userId == $profileUserId ? "Your Events" : htmlspecialchars($user['username']) . "'s Events";
            ?>
        </button>
        <button id="show-items" class="toggle-btn px-4 py-2 bg-gray-800 hover:bg-gray-900 transform hover:scale-105 rounded-md text-white transition-all duration-300">
            <?php
            echo $isLoggedIn && $userId == $profileUserId ? "Your Items" : htmlspecialchars($user['username']) . "'s Items";
            ?>
        </button>
    </div>
</div>
 
  <!-- Posty użytkownika -->
  <div id="post-container" class="container posts-container mt-6 mx-auto bg-gray-600 p-6 rounded-lg shadow-lg ">
    <h2 class="text-2xl font-bold text-white mb-4">Your Posts
    <hr class="border-t-4 w-32 border-orange-500 mb-6 mt-1">
    </h2>
    
    <?php if ($result_posts->num_rows > 0): ?>
        <div class="posts space-y-4">
            <?php while ($post = $result_posts->fetch_assoc()): ?>
                <a href="post.php?id=<?php echo $post['post_id']; ?>" class="post-link block p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition">
                    <div class="post">
                        <?php if (!empty($post['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" class="post-image mb-4 rounded-lg">
                        <?php endif; ?>
                        <div class="post-content">
                            <h3 class="text-xl text-gray-200 font-bold mb-2"><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p class="category text-gray-400 mb-2"><strong>Category: <?php echo htmlspecialchars($post['category_name']); ?></strong></p>
                            <p class="post-autor text-gray-400 mb-2"><strong>By: <?php echo htmlspecialchars($post['author_username']); ?></strong></p>
                            <p class="text-gray-300 mb-4"><?php echo $post['content']; ?></p>
                            <p class="post-date text-gray-400"><strong>Date: </strong><?php echo htmlspecialchars($post['created_at']); ?></p>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-400">No posts yet. Start creating posts!</p>
    <?php endif; ?>
</div>
 
<div id="likes-container" class="container likes-container mt-6 mx-auto bg-gray-600 p-6 rounded-lg shadow-lg" style="display: none;">
<h2 class="text-2xl font-bold text-white mb-4">Your Likes
    <hr class="border-t-4 w-32 border-orange-500 mb-6 mt-1">
    </h2>
    <?php
   $sql_like = "
    SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, categories.name AS category_name,
           COUNT(user_likes.id_likes) AS like_count, users.username AS author_username
    FROM posts
    JOIN categories ON posts.category_id = categories.category_id
    LEFT JOIN user_likes ON posts.post_id = user_likes.id_post
    LEFT JOIN users ON posts.user_id = users.user_id
    WHERE user_likes.id_user = ?
    GROUP BY posts.post_id, users.user_id, categories.name
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
        <div class="posts space-y-4">
            <?php while ($like = $result_like->fetch_assoc()):
                 $sql_check_like = "SELECT * FROM `user_likes` WHERE id_user = ? AND id_post = ?";
                    $stmt_check_like = $conn->prepare($sql_check_like);
                    $stmt_check_like->bind_param("ii", $userId, $like['post_id']);
                    $stmt_check_like->execute();
                    $result_check_like = $stmt_check_like->get_result();
                    $isLiked = $result_check_like->num_rows > 0;
                ?>
                <a href="post.php?id=<?php echo $like['post_id']; ?>" class="post-link block p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition">
                    <div class="post">
                        <?php if (!empty($like['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($like['image']); ?>" alt="Post Image" class="post-image mb-4 rounded-lg">
                        <?php endif; ?>
                        <div class="post-content">
                            <h3 class="text-xl text-white font-bold mb-2"><?php echo htmlspecialchars($like['title']); ?></h3>
                            <p class="category text-gray-400 mb-2"><strong>Category: <?php echo htmlspecialchars($like['category_name']); ?></strong></p>
                            <p class="post-autor text-gray-400 mb-2"><strong>By: <?php echo htmlspecialchars($like['author_username']); ?></strong></p>
                            <p class="text-gray-300 mb-4"><?php echo $like['content']; ?></p>
                            <p class="post-date text-gray-400"><strong>Date: </strong><?php echo htmlspecialchars($like['created_at']); ?></p>
                            <form method="POST" action="" class="relative" id="like-form-<?php echo $row['post_id']; ?>">
                                <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                                <button type="submit" name="like" class="like-btn absolute bottom-10 right-10 bg-none border-none cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="<?php echo $isLiked ? 'red' : 'none'; ?>" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                    </svg>
                                </button>

                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-400">No posts liked yet.</p>
    <?php endif; ?>
</div>
 
<div id="events-container" class="container events-container mt-6 mx-auto bg-gray-600 p-6 rounded-lg shadow-lg" style="display: none;">
<h2 class="text-2xl font-bold text-white mb-4">Your Events
    <hr class="border-t-4 w-32 border-orange-500 mb-6 mt-1">
    </h2>
    <?php if ($result_events->num_rows > 0): ?>
        <div class="events space-y-4">
            <?php while ($event = $result_events->fetch_assoc()): ?>
                <a href="event.php?id=<?php echo $event['event_id']; ?>" class="event-link block p-4 bg-gray-700 hover:bg-gray-900 rounded-lg transition">
                    <div class="event-card">
                        <div class="event-header mb-2">
                            <h3 class="text-xl font-bold text-white"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                            
                        </div>
                        <div class="event-body">
                            <p class="text-gray-400 mb-2"><strong class="text-white">Description:</strong> <?php echo htmlspecialchars($event['event_description']); ?></p>
                            <p class="text-gray-400 text-white mb-2"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                            <p class="event-date text-gray-400 "><?php echo htmlspecialchars($event['event_date']); ?></p>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-400">
            <?php
            if ($isLoggedIn && $userId == $profileUserId) {
                echo "You are not attending any events yet.";
            } else {
                echo htmlspecialchars($user['username']) . " is not attending any events.";
            }
            ?>
        </p>
    <?php endif; ?>
</div>
 
<div id="items-container" class="container items-container mt-6 mx-auto bg-gray-600 p-6 rounded-lg shadow-lg" style="display: none;">
    <h2 class="text-2xl font-bold text-white ">
        <?php echo $isLoggedIn && $userId == $profileUserId ? "Your Items" : htmlspecialchars($user['username']) . "'s Items"; ?>
    </h2>
    <hr class="border-t-4 w-32 border-orange-500 mb-6 mt-1">
    <?php if ($result_items->num_rows > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($item = $result_items->fetch_assoc()): ?>
                <a href="item_details.php?item_id=<?php echo $item['item_id']; ?>">
                <div class="item-card p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition shadow-md">
                    <?php if (!empty($item['image'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" alt="Item Image" class="w-full h-48 object-cover rounded-lg mb-4">
                    <?php else: ?>
                        <img src="public/image/default-item.png" alt="Default Item Image" class="w-full h-48 object-cover rounded-lg mb-4">
                    <?php endif; ?>
                    <div class="item-details">
                        <h3 class="text-xl font-bold text-white mb-2"><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="text-gray-400 mb-2"><strong>Price:</strong> <?php echo number_format($item['price'], 2); ?> zł</p>
                        <p class="text-gray-400 mb-2"><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
                        <p class="text-gray-400"><strong>Added on:</strong> <?php echo htmlspecialchars($item['created_at']); ?></p>
                    </div>
                    
                </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-400">No items to display.</p>
    <?php endif; ?>
</div>
 
</main>
</body>
</html>
 
<?php
$conn->close();
ob_end_flush();
?>
 