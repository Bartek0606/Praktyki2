<?php

class Sidebar {
    // Funkcja do wyświetlania sidebaru
    public function render() {
        // Zwróć cały HTML dla sidebaru
        echo '
 <!-- Header -->
        <header class="bg-gray-800 text-white p-4 shadow-md col-span-2 h-24 flex items-center fixed top-0 w-full z-10">
            <h1 class="text-lg font-bold tracking-wide mx-auto">HOBBYHUB</h1>
        </header>

        <!-- Sidebar -->
        <aside class="sidebar bg-gray-800 text-white shadow-lg h-screen pt-24 fixed left-0 w-64 z-10">
            <div class="profile-section py-6 px-4 border-b border-gray-700">
                <div class="profile-pic bg-gray-600 w-16 h-16 rounded-full mx-auto"></div>
                <p class="username mt-3 text-center font-semibold text-gray-300">Username</p>
            </div>
            <nav class="menu flex flex-col mt-4">
                <a href="admin.php" class="menu-item px-4 py-3 hover:bg-gray-700 transition">
                    Posts
                </a>
                <hr class="border-gray-700">
                <a href="events.php" class="menu-item px-4 py-3 hover:bg-gray-700 transition">
                    Events
                </a>
                <hr class="border-gray-700">
                <a href="comments.php" class="menu-item px-4 py-3 hover:bg-gray-700 transition">
                    Comments
                </a>
                <hr class="border-gray-700">
                <a href="categories.php" class="menu-item px-4 py-3 hover:bg-gray-700 transition">
                    Add new category
                </a>
            </nav>
            <div class="sidebar-bottom mt-auto px-4 py-6 border-t border-gray-700">
                <button class="w-full text-left px-4 py-3 hover:bg-red-600 rounded transition">
                    Log Out
                </button>
            </div>
        </aside>

';
    }
}
?>
