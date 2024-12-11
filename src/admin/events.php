<?php
include __DIR__ . '/Views/events_view.php';
include  '../../../db_connection.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Events</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body>
<div class="admin-panel flex">
    <main class="dashboard bg-gray-50 ml-64 mt-24 p-8 min-h-screen w-full">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Events</h2>
        <div class="flex justify-center mb-6">
            <button class="add-event-btn bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200" onclick="openAddEventPopup()">Add Event</button>
        </div>
        <?php include 'Views/events_view.php'; ?>
    </main>
</div>
<?php include 'Views/popups_events.php'; ?>
<script src="admin.js"></script>
</body>
</html>
