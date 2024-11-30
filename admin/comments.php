<?php
// Wczytanie połączenia z bazą danych oraz klasy Comment
include __DIR__ . '/../db_connection.php';  // Połączenie z bazą danych
include 'Comment.php';  // Klasa Comment
include 'CommentRenderer.php';
include_once 'sidebar_admin.php';

// Upewnij się, że sesja jest uruchomiona
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if (!$isLoggedIn) {
    header("Location: /../login.php");
    exit();
}

// Obiekt klasy Comment do obsługi komentarzy
$commentObj = new Comment($conn);

// Sprawdzenie, czy użytkownik chce usunąć komentarz
if (isset($_GET['delete_comment_id'])) {
    $comment_id = (int)$_GET['delete_comment_id'];
    $commentObj->deleteComment($comment_id); // Usunięcie komentarza i przekierowanie
}

// Pobranie danych z formularza wyszukiwania (jeśli zostały wysłane)
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Pobranie listy komentarzy
$comments = $commentObj->getAllPCom($search_query);

// Utworzenie instancji sidebaru
$sidebar = new Sidebar($conn, $userId);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @tailwind base;
        @tailwind components;
        @tailwind utilities;

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.5s ease-in-out;
        }
    </style>
</head>
<body>
<div class="admin-panel flex">
   <!-- Renderowanie sidebaru -->
   <?php echo $sidebar->getSidebarHtml(); ?>

   <main class="dashboard bg-gray-50 ml-64 mt-14 min-h-screen w-full">
      <div class="comments-container m-auto space-y-4 w-full">
         <?php
         // Renderowanie komentarzy
         $renderer = new CommentRenderer();
         echo $renderer->renderComments($comments, $search_query);
         ?>
      </div>
   </main>
</div>
</body>
</html>
