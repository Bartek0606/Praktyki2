<?php
// Funkcja rejestracji na wydarzenie
function registerForEvent($conn, $userId, $eventId) {
    $sql_check = "SELECT * FROM event_registrations WHERE user_id = ? AND event_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $userId, $eventId);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        return false; // Użytkownik już zarejestrowany
    } else {
        $sql_register = "INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)";
        $stmt_register = $conn->prepare($sql_register);
        $stmt_register->bind_param("ii", $userId, $eventId);
        $stmt_register->execute();
        return $stmt_register->affected_rows > 0; // Zwróci true, jeśli udało się zarejestrować
    }
}

// Funkcja wyrejestrowania się z wydarzenia
function unregisterFromEvent($conn, $userId, $eventId) {
    $sql_unregister = "DELETE FROM event_registrations WHERE user_id = ? AND event_id = ?";
    $stmt_unregister = $conn->prepare($sql_unregister);
    $stmt_unregister->bind_param("ii", $userId, $eventId);
    $stmt_unregister->execute();
    return $stmt_unregister->affected_rows > 0;
}
?>
