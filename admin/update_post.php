<?php
include __DIR__ . '/../db_connection.php';

// Odczyt JSON
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['post_id'], $data['category_id'])) {
    $postId = (int) $data['post_id'];
    $categoryId = (int) $data['category_id'];

    // Zaktualizuj kategorię w bazie danych
    $stmt = $conn->prepare("UPDATE posts SET category_id = ? WHERE post_id = ?");
    $stmt->bind_param("ii", $categoryId, $postId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>
