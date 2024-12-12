<?php
ob_start();
session_start(); 
include '../../Component/slider.php';
include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../Component/post.php';

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

if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
    $posts->like($userId);  
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/glowna.js" defer></script>
    <title>HobbyHub</title>
</head>

<style>
    .slider-container {
      scroll-behavior: smooth; /* Płynne przewijanie */
    }

    @keyframes draw-border {
    0% {
        border-width: 0;
        border-bottom-width: 4px;
        border-bottom-color: transparent;
    }
    50% {
        border-bottom-color: orange;
        border-left-width: 4px;
        border-left-color: orange;
    }
    100% {
        border-width: 4px;
        border-color: orange;
    }
}
.group-hover:animate-draw-border {
    animation: draw-border 1s forwards;
}


  </style>
<body class="bg-gray-900">
<header>
    <?php
        echo $navbar->render();
    ?>
      
</header>
<main class="container mx-auto">
  <?php
  render_event_slider($events_result);
  ?>
  </main>
    <div class="relative w-5/6 mx-auto h-2/4">
    <img src="../image/tlo.png" alt="Tło" class="w-full h-full object-cover filter blur mx-auto rounded-xl ">
    <div class="absolute inset-0 flex flex-col items-center justify-center">
      <h2 class="text-white text-3xl font-bold mb-2">Blog Posts Section
      <hr class="border-t-4  border-orange-500 mt-3">
      </h2>
      
    </div>
  </div>

  <section class="blog-posts w-full bg-gray-900 mt-14">

 
  <?php 
    echo $posts->render();
  ?>

<section class="w-4/6 mx-auto py-12 mb-12">
    <div class="text-left mb-8">
        <h2 class="text-2xl font-bold text-white mb-10">Posts about Technology</h2>
        <a  href="subpage.php?id=1" 
           class="px-6 py-3 bg-gray-700 text-white font-bold rounded-lg hover:bg-gray-800 transition">
            View All Technology Posts
        </a>
    </div>
    <div class="grid grid-cols-2 gap-12 bg-gray-900">
        <?php
        if ($categoryPostsResult->num_rows > 0) {
            while ($row = $categoryPostsResult->fetch_assoc()) {
                $post_url = '../templates/post.php?id=' . $row['post_id'];
                $hasImage = !empty($row['image']);

                echo "<div class='flex h-64 bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 hover:shadow-xl'>";

                if ($hasImage) {
                    echo "<div class='w-1/3 h-full'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Post Image' class='w-full h-full object-cover'>";
                    echo "</div>";
                }

                echo "<div class='p-6 w-2/3 bg-gray-800 flex flex-col justify-between'>";
                echo "<span class='text-sm uppercase text-gray-400 font-semibold'>" . htmlspecialchars($row['category_name']) . "</span>";
                echo "<a href='{$post_url}' class='text-xl font-bold text-white hover:text-blue-400 transition'>" . htmlspecialchars($row['title']) . "</a>";
                echo "<p class='text-gray-300 mt-2 line-clamp-3'>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>";
                echo "<div class='flex items-center mt-4'>";
                echo "<span class='text-sm text-gray-500'>by " . htmlspecialchars($row['author_name']) . "</span>";
                echo "<span class='text-sm text-gray-400 ml-4'>" . htmlspecialchars($row['created_at']) . "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<p class='text-center text-gray-500'>No posts found in this category.</p>";
        }
        ?>
    </div>
</section>


<section class="w-4/6 mx-auto py-12 mb-12">
    <div class="text-left mb-8">
        <h2 class="text-2xl font-bold text-white mb-10">Posts about Lifestyle</h2>
        <a href="subpage.php?id=2"  
           class="px-6 py-3 bg-gray-700 text-white font-bold rounded-lg hover:bg-gray-800 transition">
            View All Lifestyle Posts
        </a>
    </div>
    <div class="grid grid-cols-2 gap-12 bg-gray-900">
        <?php
        if ($categoryPostsResult2->num_rows > 0) {
            while ($row = $categoryPostsResult2->fetch_assoc()) {
                $post_url = '../templates/post.php?id=' . $row['post_id'];
                $hasImage = !empty($row['image']);

                echo "<div class='flex h-64 bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 hover:shadow-xl'>";

                if ($hasImage) {
                    echo "<div class='w-1/3 h-full'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Post Image' class='w-full h-full object-cover'>";
                    echo "</div>";
                }

                echo "<div class='p-6 w-2/3 bg-gray-800 flex flex-col justify-between'>";
                echo "<span class='text-sm uppercase text-gray-400 font-semibold'>" . htmlspecialchars($row['category_name']) . "</span>";
                echo "<a href='{$post_url}' class='text-xl font-bold text-white hover:text-blue-400 transition'>" . htmlspecialchars($row['title']) . "</a>";
                echo "<p class='text-gray-300 mt-2 line-clamp-3'>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>";
                echo "<div class='flex items-center mt-4'>";
                echo "<span class='text-sm text-gray-500'>by " . htmlspecialchars($row['author_name']) . "</span>";
                echo "<span class='text-sm text-gray-400 ml-4'>" . htmlspecialchars($row['created_at']) . "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<p class='text-center text-gray-500'>No posts found in this category.</p>";
        }
        ?>
    </div>
</section>



<section class="w-4/6 mx-auto py-12 mb-12">
    <div class="text-left mb-8">
        <h2 class="text-2xl font-bold text-white mb-10">Posts about Travel</h2>
        <a href="subpage.php?id=4"  
           class="px-6 py-3 bg-gray-700 text-white font-bold rounded-lg hover:bg-gray-800 transition">
            View All Travel Posts
        </a>
    </div>
    <div class="grid grid-cols-2 gap-12 bg-gray-900">
        <?php
        if ($categoryPostsResult3->num_rows > 0) {
            while ($row = $categoryPostsResult3->fetch_assoc()) {
                $post_url = '../templates/post.php?id=' . $row['post_id'];
                $hasImage = !empty($row['image']);

                echo "<div class='flex h-64 bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 hover:shadow-xl'>";

                if ($hasImage) {
                    echo "<div class='w-1/3 h-full'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Post Image' class='w-full h-full object-cover'>";
                    echo "</div>";
                }

                echo "<div class='p-6 w-2/3 bg-gray-800 flex flex-col justify-between'>";
                echo "<span class='text-sm uppercase text-gray-400 font-semibold'>" . htmlspecialchars($row['category_name']) . "</span>";
                echo "<a href='{$post_url}' class='text-xl font-bold text-white hover:text-blue-400 transition'>" . htmlspecialchars($row['title']) . "</a>";
                echo "<p class='text-gray-300 mt-2 line-clamp-3'>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>";
                echo "<div class='flex items-center mt-4'>";
                echo "<span class='text-sm text-gray-500'>by " . htmlspecialchars($row['author_name']) . "</span>";
                echo "<span class='text-sm text-gray-400 ml-4'>" . htmlspecialchars($row['created_at']) . "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<p class='text-center text-gray-500'>No posts found in this category.</p>";
        }
        ?>
    </div>
</section>

</section>
  <script>
    const slider = document.getElementById('slider');
    const prev = document.getElementById('prev');
    const next = document.getElementById('next');

    // Funkcja przesuwająca slider w lewo lub w prawo
    function slide(direction) {
      const scrollAmount = 320; // szerokość karty + odstęp
      slider.scrollLeft += direction === 'next' ? scrollAmount : -scrollAmount;
    }

    prev.addEventListener('click', () => slide('prev'));
    next.addEventListener('click', () => slide('next'));
  </script>

</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
