<?php
require_once '../../db_connection.php';

// Edit event
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
            exit(); // Ensure no further code is executed after the redirect
        } else {
            $errors[] = "Błąd: Nie udało się zaktualizować wydarzenia.";
        }

        $stmt->close();
    } else {
        $errors[] = "Błąd: Nie udało się przygotować zapytania.";
    }
}

// Delete event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event_id'])) {
    $event_id_to_delete = $_POST['delete_event_id'];

    // Debugging: Check the value of event_id_to_delete
    // var_dump($event_id_to_delete); 
    // die(); // Uncomment these lines for debugging

    $delete_query = "DELETE FROM events WHERE event_id = ?";
    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param("i", $event_id_to_delete);

        if ($stmt->execute()) {
            header("Location: events.php");
            exit(); // Ensure no further code is executed after the redirect
        } else {
            $errors[] = "Błąd: Nie udało się usunąć wydarzenia. " . $stmt->error; // Display SQL error
        }

        $stmt->close();
    } else {
        $errors[] = "Błąd: Nie udało się przygotować zapytania. " . $conn->error; // Display connection error
    }
}

// Validation for event
$errors = []; 
$formData = [ 
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

    // Validation
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

    // Add event
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
                exit(); // Ensure no further code is executed after the redirect
            } else {
                $errors[] = "Błąd: Nie udało się dodać wydarzenia. " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Błąd: Nie udało się przygotować zapytania.";
        }
    }
}

// Fetch events to display
$query = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($query);

?>
