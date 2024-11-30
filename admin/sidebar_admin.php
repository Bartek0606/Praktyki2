<?php

class Sidebar {
    private $conn;
    private $userId;

    public function __construct($conn, $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function getSidebarHtml() {
        $userData = $this->getUserData();
        $username = $userData['username'] ?? 'Guest';
        $profilePicture = $userData['profile_picture'] ?? null;

        if ($profilePicture) {
            $profilePictureSrc = 'data:image/jpeg;base64,' . base64_encode($profilePicture);
        } else {
            $profilePictureSrc = 'default_profile.png';
        }

        return '
        <header class="bg-gray-800 text-white p-4 shadow-md fixed top-0 w-full z-10">
            <h2 class="text-lg font-bold tracking-wide mx-auto">HOBBYHUB</h2>
        </header>
        <aside class="sidebar bg-gray-800 text-white h-screen fixed top-0 left-0 w-64 z-10">
            <div class="profile-section py-6 px-4 border-b border-gray-700">
                <div class="profile-pic bg-gray-600 w-16 h-16 rounded-full mx-auto">
                    <img src="' . $profilePictureSrc . '" alt="Profile Picture" class="w-full h-full rounded-full">
                </div>
                <p class="username mt-3 text-center font-semibold text-gray-300">' . htmlspecialchars($username) . '</p>
            </div>
            <nav class="menu flex flex-col">
                <a href="admin.php" class="menu-item px-4 py-3 hover:bg-gray-700 transition">Posts</a>
                <hr class="border-gray-700">
                <a href="events.php" class="menu-item px-4 py-3 hover:bg-gray-700 transition">Events</a>
                <hr class="border-gray-700">
                <a href="comments.php" class="menu-item px-4 py-3 hover:bg-gray-700 transition">Comments</a>
                <hr class="border-gray-700">
                <a href="categories.php" class="menu-item px-4 py-3 hover:bg-gray-700 transition">Add new category</a>
            </nav>
            <div class="sidebar-bottom mt-auto px-4 py-6 border-t border-gray-700">
                <form method="POST">
                    <button name="logout" class="w-full text-left px-4 py-3 hover:bg-red-600 rounded transition">Log Out</button>
                </form>
            </div>
        </aside>';
    }

    private function getUserData() {
        if ($this->userId) {
            $sql = "SELECT username, profile_picture FROM users WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $this->userId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }
}
?>
