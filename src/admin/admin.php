<?php
include __DIR__ . '/logic/posts_logic.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="admin.js"></script>
</head>
<body>
<div class="admin-panel">
   <?php echo $sidebar->getSidebarHtml(); ?>
</div>


    <?php  include __DIR__ . '/Views/posts_view.php'; ?>



</body>
</html>
