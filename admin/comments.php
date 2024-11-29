<?php
// Wczytanie połączenia z bazą danych oraz klasy Comment
include __DIR__ . '/../db_connection.php';  // Połączenie z bazą danych
include 'Comment.php';  // Klasa Comment
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
<div class="admin-panel">
   <?php $sidebar->render(); ?>

   <main class="dashboard p-8 bg-gray-50 ml-64 min-h-screen" style="padding-top: 6rem;">
      <h2 class="text-xl font-semibold text-center text-gray-800 mt-3 mb-4">All Comments</h2>

      <div class="comments-container m-auto space-y-4 w-4/6 mt-3">
         <!-- Search Form -->
         <div id="searchdiv" class="mb-8">
            <form method="GET" class="flex items-center justify-center space-x-4">
               <input type="text" name="search_query" placeholder="Search by full name" 
                      value="<?php echo htmlspecialchars($search_query); ?>" 
                      class="w-1/4 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
               <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Search</button>
            </form>
         </div>

         <?php if (empty($comments)): ?>
            <p class="text-gray-500 italic text-center">No comments found.</p>
         <?php else: ?>
            <?php foreach ($comments as $comment): ?>
               <div class="comment bg-white shadow-md rounded-lg p-6">
                  <!-- Comment Header -->
                  <div class="comment-header flex justify-between items-center mb-3">
                     <p class="font-bold text-gray-800">User: <?php echo htmlspecialchars($comment['full_name']); ?></p>
                     <p class="text-gray-500 text-sm"><?php echo date('Y-m-d H:i:s', strtotime($comment['created_at'])); ?></p>
                  </div>

                  <!-- Comment Body -->
                  <div class="comment-body mb-4">
                     <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                  </div>

                  <!-- Comment Footer -->
                  <div class="comment-footer text-right">
                     <a href="?delete_comment_id=<?php echo $comment['comment_id']; ?>" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
                        onclick="return confirm('Are you sure you want to delete this comment?');">
                        Delete
                     </a>
                  </div>
               </div>
            <?php endforeach; ?>
         <?php endif; ?>
      </div>
   </main>
</div>

</body>
</html>
