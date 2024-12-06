<?php
include __DIR__ . '/../db_connection.php'; 
include 'Comment.php';  
include 'CommentRenderer.php';
include_once 'sidebar_admin.php';

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if (!$isLoggedIn) {
    header("Location: /../login.php");
    exit();
}
$sidebar = new Sidebar($conn, $userId);

$commentObj = new Comment($conn);

if (isset($_GET['delete_comment_id'])) {
    $comment_id = (int)$_GET['delete_comment_id'];
    $commentObj->deleteComment($comment_id); 
}

$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$comments = $commentObj->getAllPCom($search_query);
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
         <?php
         $renderer = new CommentRenderer();
         echo $renderer->renderComments($comments, $search_query);
         ?>
      </div>
   </main>
</div>
</body>
</html>
