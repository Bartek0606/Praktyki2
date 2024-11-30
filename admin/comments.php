<?php
// Wczytanie połączenia z bazą danych oraz klasy Comment
include __DIR__ . '/../db_connection.php';  // Połączenie z bazą danych
include 'Comment.php';  // Klasa Comment
include 'CommentRenderer.php';
include_once 'sidebar_admin.php';

// Tworzenie obiektu klasy Sidebar
$sidebar = new Sidebar();

// Pobranie danych z formularza wyszukiwania (jeśli zostały wysłane)
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Utworzenie obiektu klasy Comment i pobranie komentarzy
$commentObj = new Comment($conn);
$comments = $commentObj->getAllPCom($search_query);  // Pobranie komentarzy z możliwością filtrowania

// Sprawdzamy, czy został wysłany request do usunięcia komentarza
if (isset($_GET['delete_comment_id'])) {
    $comment_id = (int)$_GET['delete_comment_id'];
    // Usuwamy komentarz
    $commentObj->deleteComment($comment_id);
}
// Tworzenie obiektu klasy CommentRenderer i generowanie HTML
$renderer = new CommentRenderer();
echo $renderer->renderComments($comments, $search_query);
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
<div class="admin-panel flex">
   <?php $sidebar->render(); ?>

   <main class="dashboard p-8 bg-gray-50 min-h-screen w-full ml-64">

      <div class="comments-container m-auto space-y-4 w-4/6 mt-28">
         <?php
         $renderer = new CommentRenderer();
         echo $renderer->renderComments($comments, $search_query);
         ?>
      </div>
   </main>
</div>



</body>
</html>
