<?php
// Ładujemy plik logiki
include __DIR__ . '/logic/categories_logic.php';
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

<body class="bg-gray-50 text-gray-700">
<div class="admin-panel">
    <?php echo $sidebar->getSidebarHtml(); ?>

    <?php // Ładujemy widok
include __DIR__ . '/Views/categories_view.php'; ?>
</div>
</body>
</html>
