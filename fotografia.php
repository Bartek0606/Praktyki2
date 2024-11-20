<?php
session_start(); 
include 'db_connection.php'; 

$sql = "
    SELECT DISTINCT c.content, u.username, u.profile_picture_url 
    FROM comments c
    JOIN posts p ON c.post_id = p.post_id
    JOIN users u ON c.user_id = u.user_id
    WHERE p.category_id = 5"; 

$result = $conn->query($sql);

$sql1 = "
    SELECT DISTINCT 
    posts.title, 
    posts.content, 
    posts.image_url, 
    posts.created_at, 
    users.username, 
    users.profile_picture_url, 
    categories.name AS category_name 
FROM 
    posts 
JOIN 
    users ON posts.user_id = users.user_id
JOIN 
    categories ON posts.category_id = categories.category_id
WHERE 
    categories.name = 'Photography'
ORDER BY 
    posts.created_at DESC;
";
$result1 = $conn->query($sql1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="fotografia.css">
    <link rel="stylesheet" href="glowna.css">
    <script src="fotografia.js" defer></script>
    <title>Blog o fotografii</title>
</head>
<body>
    
<header>
    <nav class="navbar">
        <div class="logo">
            <h1>Odkrywaj Fotografię z nami!</h1>
        </div>
        <ul class="nav-links">
            <li><a href="#podstawy-fotografii">Podstawy fotografii</a></li>
            <li><a href="#posty">Posty</a></li>
            <li><a href="#opinie">Opinie</a></li>
            <li><a href="#o-nas">O nas</a></li>
            <li><a href="#najpopularniejsze-posty">Najpopularniejsze posty</a></li>
        </ul>
        <div class="auth-buttons">
            <button class="btn register-btn">Zarejestruj się</button>
            <button class="btn login-btn">Zaloguj się</button>
        </div>
    </nav>
</header>

<section id="podstawy-fotografii" class="hero-section">
    <div class="hero-container">
        <img src="zdjecie_foto.png">
        <div class="hero-content">
            <h1>Dowiedz się wszystkiego o fotografii!</h1> 
            <hr class="hero-divider">
            <p>
                <strong>Witaj na blogu fotograficznym!</strong> 🌍📸 - To przestrzeń, w której pasja do fotografii spotyka się z historiami, emocjami i inspiracjami. Niezależnie od tego, czy jesteś profesjonalistą, początkującym fotografem, czy po prostu miłośnikiem pięknych obrazów – znajdziesz tutaj coś dla siebie.
            </p>
            <p>
                Na blogu znajdziesz:
                <br>
                <strong>Poradniki fotograficzne</strong> – Praktyczne wskazówki dotyczące kompozycji, oświetlenia, wyboru sprzętu i postprodukcji.
                <br>
                <strong>Historie zza obiektywu</strong> – Opowieści o wyjątkowych miejscach, wydarzeniach i ludziach, którzy stali się tematem moich zdjęć.
                <br>
                <strong>Galerie tematyczne</strong> – Od majestatycznych krajobrazów, przez dynamiczne ujęcia z miast, aż po intymne portrety.
            </p>
            <p>Zaczynajmy wspólną przygodę!</p>
            <hr class="hero-divider">
        </div>
    </div>
    <div class="search-section">
        <h2>Znajdź to, czego potrzebujesz</h2>
        <form class="search-form">
            <input type="text" placeholder="Napisz, czego chcesz się dowiedzieć">
            <button type="submit">Szukaj</button>
        </form>
    </div>
</section>

<section id="opinie" class="reviews-section">
    <h2 class="reviews-title">Opinie użytkowników o blogu</h2>
    <div class="reviews-slider">
        <button class="reviews-prev-button">&larr;</button> <!-- Strzałka w lewo -->
        <div class="reviews-container">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="review">';
                    echo '<img src="' . $row['profile_picture_url'] . '" alt="Zdjęcie użytkownika" class="review-image">';
                    echo '<p class="review-username">' . $row['username'] . '</p>';
                    echo '<p class="review-text">"' . $row['content'] . '"</p>';
                    echo '</div>';
                }
            } else {
                echo "Brak recenzji dotyczących fotografii.";
            }
            ?>
        </div>
        <button class="reviews-next-button">&rarr;</button> <!-- Strzałka w prawo -->
    </div>
</section>

<div class="tlo_posty">
    <section id="posty" class="posts-section">
        <h2 class="posts-title">Posty o podstawach fotografii</h2>
        <a href="#" class="see-more">Zobacz więcej postów</a>

        <div class="posts-container">
            <?php
            if ($result1->num_rows > 0) {
                while($row1 = $result1->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<img src='" . $row1["image_url"] . "' alt='" . $row1["title"] . "' class='post-image'>";
                    echo "<h3 class='post-title'>" . $row1["title"] . "</h3>";
                    echo "<p><strong>Autor:</strong> " . $row1["username"] . " | <strong>Data:</strong> " . $row1["created_at"] . "</p>";
                    echo "<a href='#' class='read-more'>Czytaj więcej</a>";
                    echo "</div>";
                }
            } else {
                echo "Brak postów o fotografii.";
            }
            ?>
        </div>
    </section>
</div>

<div class="tlo_posty_popularne">
    <section id="najpopularniejsze-posty" class="most-liked-posts-section">
        <h2 class="most-liked-posts-title">Najczęściej polubiane posty</h2>
        <a href="#" class="see-more-posts">Zobacz więcej postów</a>

        <div class="most-liked-posts-container">
            <div class="most-liked-post">
                <img src="wesela.png" alt="Post 1" class="post-image">
                <h3 class="most-liked-post-title">Fotografia ślubna na światowym poziomie</h3>
                <a href="#" class="read-more">Czytaj więcej</a>
            </div>
            <div class="most-liked-post">
                <img src="plenery.png" alt="Post 2" class="post-image">
                <h3 class="most-liked-post-title">Top 5 najpiękniejszych plenerów fotograficznych w Polsce</h3>
                <a href="#" class="read-more">Czytaj więcej</a>
            </div>
            <div class="most-liked-post">
                <img src="zlota.png" alt="Post 3" class="post-image">
                <h3 class="most-liked-post-title">Fotografowanie w złotej godzinie: Poradnik profesjonalisty</h3>
                <a href="#" class="read-more">Czytaj więcej</a>
            </div>
            <div class="most-liked-post">
                <img src="podroze.png" alt="Post 4" class="post-image">
                <h3 class="most-liked-post-title">Tajniki fotografii podróżniczej: Uchwyć chwilę w kadrze</h3>
                <a href="#" class="read-more">Czytaj więcej</a>
            </div>
            <div class="most-liked-post">
                <img src="event.png" alt="Post 5" class="post-image">
                <h3 class="most-liked-post-title">Jak uchwycić emocje podczas eventów? Poradnik od profesjonalisty</h3>
                <a href="#" class="read-more">Czytaj więcej</a>
            </div>
        </div>
    </section>
</div>
<script>
    // Skrypt do przewijania do odpowiednich sekcji

    document.addEventListener("DOMContentLoaded", function() {
        // Znajdź wszystkie linki nawigacyjne
        const navLinks = document.querySelectorAll('.nav-links a');

        // Funkcja do przewijania do odpowiedniej sekcji
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();  // Zapobiegamy standardowemu działaniu linku

                // Pobieramy nazwę sekcji z href (np. #popular-posts)
                const targetId = link.getAttribute('href').substring(1);  // Usuwamy "#" z href
                const targetSection = document.getElementById(targetId);

                if (targetSection) {
                    // Przewijamy stronę do tej sekcji
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>

</body>
</html>
