<?php
ob_start();
session_start(); 

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../navbar.css">

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
<main class="container">
<div class="w-full mx-auto max-w-5xl px-4">
    <div class="flex justify-between items-center mb-4">
      <div class="text-2xl font-bold text-white">Events Section
      <hr class="border-t-4 mx-auto w-full border-orange-500 mb-6 mt-1">
      </div>
      <!-- Strzałki -->
      <div class="flex space-x-2">
        <button id="prev" class="bg-gray-600 p-2 rounded-full text-white hover:bg-gray-500">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <button id="next" class="bg-gray-600 p-2 rounded-full text-white hover:bg-gray-500">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>
    </div>
    <!-- Slider -->
    <div id="slider" class="slider-container flex overflow-hidden space-x-4 mb-14">

    <?php
if ($events_result->num_rows > 0) {
    while ($event = $events_result->fetch_assoc()) {
        $formatted_date = date("F j, Y, g:i a", strtotime($event['event_date']));
        $event_url = 'event.php?id=' . $event['event_id']; // URL to the event details page

        // Make the event container bigger and ensure no scrollbar on hover
        echo "<a href='" . $event_url . "' class='bg-gray-800 p-6 rounded-lg flex-none w-96 transition-transform transform hover:scale-105'>"; // Tailwind for scaling
        echo "<div class='text-orange-400 text-sm mb-2'>" . htmlspecialchars($formatted_date) . "</div>";
        echo "<div class='text-lg text-white font-semibold'>" . htmlspecialchars($event['event_name']) . "</div>";
        echo "<div class='text-sm text-white mt-2'>" . htmlspecialchars($event['location']) . "</div>";
        echo "</a>"; // Close the anchor tag
    }
} else {
    echo "<p class='text-center'>No events available.</p>";
}
?>
  </div>
  </div>
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

  <div class="sort-menu  rounded-lg text-gray-300 flex items-center ">
  
    <form method="GET" action="" class="flex items-center mx-auto space-x-3">
        <label for="sort" class="font-semibold text-gray-400">Sort by:</label>
        <select 
            name="sort" 
            id="sort" 
            class="bg-gray-800 text-gray-300 border border-gray-600 rounded-lg px-3 py-1 focus:ring-2 focus:ring-orange-400 focus:outline-none" 
            onchange="this.form.submit()"
        >
            <option value="newest" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'newest' ? 'selected' : ''; ?>>Newest</option>
            <option value="oldest" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'selected' : ''; ?>>Oldest</option>
            <option value="likes" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'likes' ? 'selected' : ''; ?>>Most Liked</option>
        </select>
        
    </form>

</div>


    <?php 
        echo $posts->render();
    ?>
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
