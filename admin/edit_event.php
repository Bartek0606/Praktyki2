<?php
// Łączenie z bazą danych
include __DIR__ . '/../db_connection.php';

// Sprawdzamy, czy formularz został wysłany do edycji
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    // Pobieramy dane z formularza
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];

    // Zapytanie do aktualizacji wydarzenia
    $query = "UPDATE events SET 
                event_name = ?, 
                event_date = ?, 
                location = ?, 
                event_description = ? 
              WHERE event_id = ?";

    // Przygotowanie zapytania
    if ($stmt = $conn->prepare($query)) {
        // Powiązanie parametrów
        $stmt->bind_param("ssssi", $event_name, $event_date, $event_location, $event_description, $event_id);

        // Wykonanie zapytania
        if ($stmt->execute()) {
            // Po udanej edycji przekierowanie do strony z wydarzeniami
            header("Location: events.php");
            exit();
        } else {
            echo "Błąd: Nie udało się zaktualizować wydarzenia.";
        }

        // Zamknięcie zapytania
        $stmt->close();
    } else {
        echo "Błąd: Nie udało się przygotować zapytania.";
    }
}
?>