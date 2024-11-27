<?php

class Delete_post {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn; // Przypisanie połączenia z bazą danych
    }

     // Metoda do usuwania posta
    public function deletePost($postId) {
        // Przygotowanie zapytania do usunięcia posta
        $sql = "DELETE FROM posts WHERE post_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $postId);

        // Wykonanie zapytania
        $stmt->execute();
        $stmt->close();   // Zamykanie zasobów
    }

    // Metoda do obsługi żądania usunięcia
    public function handleDeleteRequest() {
        // Sprawdzenie, czy jest żądanie POST z ID posta do usunięcia
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
            $postId = intval($_POST['delete_post_id']); // Konwersja na int dla bezpieczeństwa
            $this->deletePost($postId); // Usunięcie posta

            // Przekierowanie po wykonaniu operacji
            header("Location: admin.php");
            exit;
        }
    }
}
