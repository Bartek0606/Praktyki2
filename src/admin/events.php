<?php
include __DIR__ . '/../../db_connection.php';
include './logic/events_logic.php';
include './Views/popups_events.php';
include_once './Views/sidebar_admin.php';

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if (!$isLoggedIn) {
    header("Location: /../../public/templates/login.php");
    exit;
}

$sidebar = new Sidebar($conn, $userId);
$popupRenderer = new Event_Popups_Renderer();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="admin.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="admin-panel flex">
    <?php echo $sidebar->getSidebarHtml(); ?>
    <?php include './Views/events_view.php'; ?>
    
</div>
<?php
echo $popupRenderer->renderAddEventPopup($errors, $formData);
echo $popupRenderer->renderEditEventPopup();
?>

</body>
</html>
