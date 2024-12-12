<?php
include __DIR__ . '/../../db_connection.php';
include 'popups_events.php';
include_once 'sidebar_admin.php';

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

if (!$isLoggedIn) {
    header("Location: /../../public/templates/login.php");
    exit;
}

$sidebar = new Sidebar($conn, $userId);
$popupRenderer = new Event_Popups_Renderer();
$query = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event_id'])) {
    $event_id_to_delete = $_POST['delete_event_id'];
    $delete_query = "DELETE FROM events WHERE event_id = ?";
    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param("i", $event_id_to_delete);
        if ($stmt->execute()) {
            header("Location: events.php");
            exit();
        } else {
            echo "Błąd: Nie udało się usunąć wydarzenia.";
        }
        $stmt->close();
    } else {
        echo "Błąd: Nie udało się przygotować zapytania.";
    }
}

$errors = []; // Tablica błędów
$formData = [ // Dane formularza do ponownego wyświetlenia w przypadku błędu
    'event_name' => '',
    'event_date' => '',
    'event_location' => '',
    'event_description' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $formData['event_name'] = trim($_POST['event_name']);
    $formData['event_date'] = trim($_POST['event_date']);
    $formData['event_location'] = trim($_POST['event_location']);
    $formData['event_description'] = trim($_POST['event_description']);

    // Walidacja
    if (empty($formData['event_name'])) {
        $errors['event_name'] = "Event name cannot be empty.";
    }
    if (empty($formData['event_date'])) {
        $errors['event_date'] = "Event date cannot be empty.";
    }
    if (empty($formData['event_location'])) {
        $errors['event_location'] = "Event location cannot be empty.";
    }
    if (empty($formData['event_description'])) {
        $errors['event_description'] = "Event description cannot be empty.";
    }

    if (empty($errors)) {
        $result = $conn->query("SELECT MAX(event_id) AS max_id FROM events");
        $row = $result->fetch_assoc();
        $new_event_id = $row['max_id'] + 1; 

        $insert_query = "INSERT INTO events (event_id, event_name, event_description, event_date, location) 
                         VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($insert_query)) {
            $stmt->bind_param(
                "issss",
                $new_event_id,
                $formData['event_name'],
                $formData['event_description'],
                $formData['event_date'],
                $formData['event_location']
            );
            if ($stmt->execute()) {
                header("Location: events.php");
                exit();
            } else {
                echo "Błąd: Nie udało się dodać wydarzenia. " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Błąd: Nie udało się przygotować zapytania.";
        }
    }
}
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
    <?php include __DIR__ . './Views/events_view.php'; ?>
    
</div>
<?php
echo $popupRenderer->renderAddEventPopup($errors, $formData);
echo $popupRenderer->renderEditEventPopup();
?>

</body>
</html>
