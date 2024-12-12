<?php
include __DIR__ . '/../db_connection.php';

//Edit_event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];

    $query = "UPDATE events SET event_name = ?, event_date = ?, location = ?, event_description = ? WHERE event_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param("ssssi", $event_name, $event_date, $event_location, $event_description, $event_id);

        if ($stmt->execute()) {
            header("Location: events.php");
            exit();
        } else {
            echo "Błąd: Nie udało się zaktualizować wydarzenia.";
        }

        $stmt->close();
    } else {
        echo "Błąd: Nie udało się przygotować zapytania.";
    }
}



?>