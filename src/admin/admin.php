<?php
include __DIR__ . '/../../db_connection.php';
include './logic/post_logic.php';
include_once './Views/sidebar_admin.php';

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
// Obsługa wylogowania
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: /../../public/templates/login.php");
    exit;
}


if (!$isLoggedIn) {
    header("Location: /../../public/templates/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveChanges'])) {
    $isUpdated = $adminPanel->handleSaveChanges($_POST);
    if ($isUpdated) {
        $adminPanel->redirectAfterSave();
    } else {
        echo "Błąd podczas zapisywania danych.";
    }
}

$sidebar = new Sidebar($conn, $userId);
$adminPanel = new Edit_Post($conn); // Przeniesiono inicjalizację tutaj

$postToEdit = null;
if (isset($_GET['edit_post_id'])) {
    $editPostId = intval($_GET['edit_post_id']);
    $postToEdit = $adminPanel->getPostToEdit($editPostId); // Teraz $adminPanel jest poprawnie zainicjalizowany
}

$posts = $adminPanel->getAllPosts();
$categories = $adminPanel->getCategories();
$postManager = new Delete_post($conn);
$postManager->handleDeleteRequest();


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
<div class="admin-panel">
   <?php echo $sidebar->getSidebarHtml(); ?>

<?php include './Views/post_view.php'; ?>

</div>

<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>
<?php include './Views/popups_view_post.php'; ?>



<script>
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".editpost_button");
    const popupModal = document.getElementById("popupModal");
    const overlay = document.getElementById("overlay");
    const cancelEdit = document.getElementById("cancelEdit");

    editButtons.forEach(button => {
        button.addEventListener("click", () => {
            const postId = button.getAttribute("data-post-id");
            window.location.href = "?edit_post_id=" + postId;
        });
    });

    <?php if ($postToEdit): ?>
    popupModal.classList.remove("hidden");
    overlay.classList.remove("hidden");
    <?php endif; ?>

    if (cancelEdit) {
        cancelEdit.addEventListener("click", () => {
            popupModal.classList.add("hidden");
            overlay.classList.add("hidden");
            window.location.href = "admin.php";
        });
    }
});

</script>
</body>
</html>
