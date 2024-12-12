<?php
ob_start();
session_start(); 
include '../../Component/slider.php';
include './all_post_view.php';
include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../Component/post.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$category_name = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

$Allposts = new AllPostRender($conn, $isLoggedIn, $category_name, $userId); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/glowna.js" defer></script>
    <title>HobbyHub</title>
</head>
<body class="bg-gray-900">
<header>
    <?php
        echo $navbar->render();
    ?>
      
</header>
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
    echo $Allposts->render();
  ?>


</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
