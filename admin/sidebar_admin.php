<?php

class Sidebar {
    // Funkcja do wyświetlania sidebaru
    public function render() {
        // Zwróć cały HTML dla sidebaru
        echo '
        <header>
            <h1 class="tittle">HOBBYHUB</h1>
        </header>
        <aside class="sidebar">
            <hr class="hr_nav">
            <div class="profile-section">
                <div class="profile-pic"></div>
                <p class="username">Username</p>
            </div>
            <nav class="menu">
                <hr class="hrbutton">
                <a href="admin.php"> <button class="menu-button">Posts</button></a>
                <hr class="hrbutton">
                <a href="events.php"> <button class="menu-button">Events</button></a>
                <hr class="hrbutton">
                <a href="comments.php"><button class="menu-button">Comments</button></a>
                <hr class="hrbutton">
                <a href="categories.php"> <button class="menu-button">Add new category</button></a>
                <hr class="hrbutton">
            </nav>
            <hr class="hrbutton">
            <div class="sidebar-bottom">
                <button class="logout-button">
                    <span>Log Out</span>
                </button>
            </div>
        </aside>';
    }
}

?>
