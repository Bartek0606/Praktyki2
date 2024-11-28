<?php

class Comment {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Metoda pobierająca wszystkie komentarze z pełnym imieniem użytkownika, posortowane po pełnym imieniu użytkownika
    public function getAllPCom($search_query = '') {
        // Przygotowanie zapytania SQL
        $sql = "
            SELECT comments.comment_id, comments.content, comments.created_at, users.full_name
            FROM comments
            LEFT JOIN users ON comments.user_id = users.user_id
            WHERE users.full_name LIKE ?
            ORDER BY users.full_name ASC, comments.created_at DESC
        ";

        // Przygotowanie zapytania
        $stmt = $this->conn->prepare($sql);
        
        // Parametr wyszukiwania (dodajemy % na początku i końcu, żeby dopasować część nazwy użytkownika)
        $search_param = "%" . $search_query . "%";
        
        // Wiązanie parametru
        $stmt->bind_param("s", $search_param);

        // Wykonanie zapytania
        $stmt->execute();
        $result = $stmt->get_result();

        // Zwracamy wyniki jako tablicę
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Metoda do usuwania komentarza
    public function deleteComment($comment_id) {
        // Zapytanie SQL do usunięcia komentarza
        $sql = "DELETE FROM comments WHERE comment_id = ?";

        // Przygotowanie zapytania
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $comment_id);  // Wiążemy parametr (id komentarza)
        $stmt->execute();

        // Jeśli komentarz został usunięty, przekierowujemy z powrotem
        if ($stmt->affected_rows > 0) {
            header("Location: comments.php");  // Przekierowanie, by zaktualizować widok
            exit();
        } else {
            echo "Error deleting comment.";
        }
    }

    // Metoda umożliwiająca wyszukiwanie komentarzy po nazwie użytkownika
    public function searchCommentsByUser($search_query) {
        return $this->getAllPCom($search_query);
    }
}
