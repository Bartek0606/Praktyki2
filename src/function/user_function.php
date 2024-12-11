<?
function getUserData($conn, $profileUserId) {
    $sql_user = "SELECT username, email, full_name, bio, profile_picture FROM users WHERE user_id = ?";
    $stmt_user = $conn->prepare($sql_user);
    if ($stmt_user === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt_user->bind_param("i", $profileUserId);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    return $result_user->fetch_assoc();
}

function getUserPosts($conn, $profileUserId) {
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
    return $stmt_posts->get_result();
}

function getUserEvents($conn, $profileUserId) {
    $sql_events = "
        SELECT events.event_id, events.event_name, events.event_description, events.event_date, events.location
        FROM events
        JOIN event_registrations ON events.event_id = event_registrations.event_id
        WHERE event_registrations.user_id = ?
        ORDER BY events.event_date DESC;
    ";

    $stmt_events = $conn->prepare($sql_events);
    $stmt_events->bind_param("i", $profileUserId);
    $stmt_events->execute();
    return $stmt_events->get_result();
}

function getUserItems($conn, $profileUserId) {
    $sql_items = "
        SELECT items.item_id, items.name, items.description, items.image, items.price, items.created_at
        FROM items
        WHERE items.user_id = ?
        ORDER BY items.created_at DESC
    ";

    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param("i", $profileUserId);
    $stmt_items->execute();
    return $stmt_items->get_result();
}

function getFollowStatus($conn, $userId, $profileUserId) {
    $sql_follow_check = "SELECT * FROM user_follows WHERE follower_id = ? AND following_id = ?";
    $stmt_follow_check = $conn->prepare($sql_follow_check);
    $stmt_follow_check->bind_param("ii", $userId, $profileUserId);
    $stmt_follow_check->execute();
    $result_follow_check = $stmt_follow_check->get_result();
    return $result_follow_check->num_rows > 0;
}

function getFollowersCount($conn, $profileUserId) {
    $sql_followers_count = "SELECT COUNT(*) AS followers_count FROM user_follows WHERE following_id = ?";
    $stmt_followers_count = $conn->prepare($sql_followers_count);
    $stmt_followers_count->bind_param("i", $profileUserId);
    $stmt_followers_count->execute();
    $result_followers_count = $stmt_followers_count->get_result();
    return $result_followers_count->fetch_assoc()['followers_count'];
}

function getFollowingCount($conn, $profileUserId) {
    $sql_following_count = "SELECT COUNT(*) AS following_count FROM user_follows WHERE follower_id = ?";
    $stmt_following_count = $conn->prepare($sql_following_count);
    $stmt_following_count->bind_param("i", $profileUserId);
    $stmt_following_count->execute();
    $result_following_count = $stmt_following_count->get_result();
    return $result_following_count->fetch_assoc()['following_count'];
}

function handleFollowUnfollow($conn, $userId, $profileUserId, $isFollowing) {
    if ($isFollowing) {
        $sql_unfollow = "DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?";
        $stmt_unfollow = $conn->prepare($sql_unfollow);
        $stmt_unfollow->bind_param("ii", $userId, $profileUserId);
        $stmt_unfollow->execute();
    } else {
        $sql_follow = "INSERT INTO user_follows (follower_id, following_id) VALUES (?, ?)";
        $stmt_follow = $conn->prepare($sql_follow);
        $stmt_follow->bind_param("ii", $userId, $profileUserId);
        $stmt_follow->execute();
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $profileUserId);
    exit();
}

function getPostsCount($conn, $profileUserId) {
    $sql_posts_count = "SELECT COUNT(*) AS posts_count FROM posts WHERE user_id = ?";
    $stmt_posts_count = $conn->prepare($sql_posts_count);
    $stmt_posts_count->bind_param("i", $profileUserId);
    $stmt_posts_count->execute();
    $result_posts_count = $stmt_posts_count->get_result();
    return $result_posts_count->fetch_assoc()['posts_count'];
}

function getLikedPosts($conn, $userId) {
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
    return $stmt_like->get_result();
}

function handleLikeUnlike($conn, $userId, $post_id) {
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

function checkIfLiked($conn, $userId, $post_id) {
    $sql_check_like = "SELECT * FROM `user_likes` WHERE id_user = ? AND id_post = ?";
    $stmt_check_like = $conn->prepare($sql_check_like);
    $stmt_check_like->bind_param("ii", $userId, $post_id);
    $stmt_check_like->execute();
    $result_check_like = $stmt_check_like->get_result();
    return $result_check_like->num_rows > 0;
}
?>
