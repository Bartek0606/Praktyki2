<?php
ob_start();
session_start(); 
include '../../Component/slider.php';
include '../../../db_connection.php';
include '../../Component/navbar.php';
include '../../Component/post.php';
include '../../function/index_function.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>HobbyHub</title>
</head>
<style>
    .slider-container {
      scroll-behavior: smooth; /* Płynne przewijanie */
    }
</style>
<body class="bg-gray-900"> 
<header>
    <?php echo $navbar->render(); ?>  
</header>

<main class="container mx-auto">
  <?php render_event_slider($events_result); ?>
  </main>

  <?php echo $posts->render(); ?>

  <?php include '../../Component/view/posts_categories_view.php'; ?>

  <script src='../js/glowna.js'></script>
</body>
</html>

<?php
include '../../Component/view/footer.php';
$conn->close();
ob_end_flush();
?>
