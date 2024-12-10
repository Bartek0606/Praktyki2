<?php
include __DIR__ . '/../../db_connection.php';
include __DIR__ . '/logic/comment_logic.php';
include_once 'sidebar_admin.php';

$sidebar = new Sidebar($conn, $userId);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="admin-panel flex">
   <?php echo $sidebar->getSidebarHtml(); ?>
   <main class="dashboard bg-gray-50 ml-64 mt-14 min-h-screen w-full">
      <div class="comments-container m-auto space-y-4 w-full">
      <?php // Ładujemy widok
            include __DIR__ . '/Views/comment_view.php'; ?>
      </div>
   </main>
</div>
</body>
</html>
