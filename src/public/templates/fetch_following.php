<?php
include '../../../db_connection.php';

if (!isset($_GET['user_id'])) {
    echo "User ID not provided.";
    exit();
}

$userId = intval($_GET['user_id']);

$sql = "
    SELECT users.username, users.full_name, users.profile_picture 
    FROM user_follows 
    JOIN users ON user_follows.following_id = users.user_id 
    WHERE user_follows.follower_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="flex items-center mb-4">';
        if (!empty($row['profile_picture'])) {
            echo '<img class="w-12 h-12 rounded-full mr-4" src="data:image/jpeg;base64,' . base64_encode($row['profile_picture']) . '" alt="Profile Picture">';
        } else {
            echo '<img class="w-12 h-12 rounded-full mr-4" src="/src/public/image/default.png" alt="Default Picture">';
        }
        echo '<div>';
        echo '<p class="text-white font-bold">' . htmlspecialchars($row['username']) . '</p>';
        echo '<p class="text-gray-400">' . htmlspecialchars($row['full_name']) . '</p>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p class="text-gray-300">No following users found.</p>';
}
?>
