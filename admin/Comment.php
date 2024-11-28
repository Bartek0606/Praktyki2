<?php

class Comment {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Metoda pobierająca wszystkie komentarze
    public function getAllPCom() {
        // Zapytanie SQL do pobrania komentarzy
        $sql = "
            SELECT comment_id, content, user_id, created_at
            FROM comments
            ORDER BY created_at DESC
        ";

        // Przygotowanie i wykonanie zapytania
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        // Zwracamy wyniki jako tablicę asocjacyjną
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
