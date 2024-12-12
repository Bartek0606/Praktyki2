<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$category_name = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

$posts = new PostRender($conn, $isLoggedIn, $category_name, $userId); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}

$sql_events = "SELECT event_id, event_name, event_description, event_date, location 
               FROM events 
               ORDER BY event_date ASC"; 

$events_result = $conn->query($sql_events);


// Pobieranie postów z kategorii o ID = 1
$categoryPostsSql = "
    SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, posts.is_question,
           categories.name AS category_name, users.username AS author_name,
           COUNT(user_likes.id_post) AS like_count
    FROM posts
    JOIN categories ON posts.category_id = categories.category_id
    JOIN users ON posts.user_id = users.user_id
    LEFT JOIN user_likes ON posts.post_id = user_likes.id_post
    WHERE posts.category_id = 1
    GROUP BY posts.post_id
    ORDER BY posts.created_at DESC
    LIMIT 4
";

$categoryPostsResult = $conn->query($categoryPostsSql);

// Pobieranie postów z kategorii o ID = 2
$categoryPostsSql2 = "
    SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, posts.is_question,
           categories.name AS category_name, users.username AS author_name,
           COUNT(user_likes.id_post) AS like_count
    FROM posts
    JOIN categories ON posts.category_id = categories.category_id
    JOIN users ON posts.user_id = users.user_id
    LEFT JOIN user_likes ON posts.post_id = user_likes.id_post
    WHERE posts.category_id = 2
    GROUP BY posts.post_id
    ORDER BY posts.created_at DESC
    LIMIT 4
";

$categoryPostsResult2 = $conn->query($categoryPostsSql2);

// Pobieranie postów z kategorii o ID = 3
$categoryPostsSql3 = "
    SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.image, posts.is_question,
           categories.name AS category_name, users.username AS author_name,
           COUNT(user_likes.id_post) AS like_count
    FROM posts
    JOIN categories ON posts.category_id = categories.category_id
    JOIN users ON posts.user_id = users.user_id
    LEFT JOIN user_likes ON posts.post_id = user_likes.id_post
    WHERE posts.category_id = 4
    GROUP BY posts.post_id
    ORDER BY posts.created_at DESC
    LIMIT 4
";

$categoryPostsResult3 = $conn->query($categoryPostsSql3);

?>